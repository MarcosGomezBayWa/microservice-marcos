<?php

namespace MyApi;

use BayWaReLusy\JwtAuthentication\InvalidTokenException;
use BayWaReLusy\JwtAuthentication\TokenService;
use BayWaReLusy\UserManagement\MachineUserIdentity;
use BayWaReLusy\UserManagement\UserIdentity;
use Doctrine\Common\Annotations\AnnotationReader;
use Laminas\ApiTools\MvcAuth\Identity\AuthenticatedIdentity;
use Laminas\ApiTools\MvcAuth\Identity\GuestIdentity;
use Laminas\ApiTools\MvcAuth\Identity\IdentityInterface;
use Laminas\ApiTools\MvcAuth\MvcAuthEvent;
use Laminas\ApiTools\Provider\ApiToolsProviderInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\Cache\Psr\CacheItemPool\CacheItemPoolDecorator;
use Laminas\Config\Config;
use Laminas\EventManager\EventInterface;
use Laminas\Http\Header\GenericHeader;
use Laminas\Http\Request;
use Laminas\ModuleManager\Feature\AutoloaderProviderInterface;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ServiceManager\ServiceManager;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="BayWa r.e. Example API",
 *     description="This is the BayWa r.e. Example API documentation. More about BayWa r.e. on https://baywa-re.com/",
 *     version="1.1.0",
 *     @OA\Contact(
 *         email="pascal.paulis@baywa-re.com"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="api_auth",
 *   type="oauth2",
 *   @OA\Flow(
 *      tokenUrl=TOKEN_URL,
 *      flow="clientCredentials",
 *      scopes={}
 *   )
 * )
 *
 * @OA\Server(url=API_HOST)
 *
 * @OA\Schema(
 *     schema="HalCollectionLinks",
 *     @OA\Property(
 *         property="_links",
 *         type="object",
 *         @OA\Property(
 *             property="self",
 *             type="object",
 *             @OA\Property(
 *                 property="href",
 *                 type="string"
 *             )
 *         ),
 *         @OA\Property(
 *             property="first",
 *             type="object",
 *             @OA\Property(
 *                 property="href",
 *                 type="string"
 *             )
 *         ),
 *         @OA\Property(
 *             property="last",
 *             type="object",
 *             @OA\Property(
 *                 property="href",
 *                 type="string"
 *             )
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="HalCollectionCounts",
 *     @OA\Property(
 *         property="page_count",
 *         type="integer",
 *         example=42
 *     ),
 *     @OA\Property(
 *         property="page_size",
 *         type="integer",
 *         example=100
 *     ),
 *     @OA\Property(
 *         property="total_items",
 *         type="integer",
 *         example=5359
 *     ),
 *     @OA\Property(
 *         property="page",
 *         type="integer",
 *         example=3
 *     )
 * )
 */
class Module implements
    ApiToolsProviderInterface,
    ConfigProviderInterface,
    BootstrapListenerInterface
{
    public function getConfig()
    {
        $config = new Config(include __DIR__ . '/../config/module.config.php');
        $config->merge(new Config(include __DIR__ . '/../config/services.php'));
        $config->merge(new Config(include __DIR__ . '/../config/validators.php'));

        return $config;
    }

    public function onBootstrap(EventInterface $e)
    {
        /** @phpstan-ignore-next-line */
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach('authentication', [$this, 'onAuthentication'], 10000);

        // Ignore Annotations from OpenApi in Doctrine AnnotationReader
        AnnotationReader::addGlobalIgnoredName('OA\Schema');
        AnnotationReader::addGlobalIgnoredName('OA\Property');
        AnnotationReader::addGlobalIgnoredName('OA\Tag');
        AnnotationReader::addGlobalIgnoredName('OA\Items');
    }

//    public function getAutoloaderConfig()
//    {
//        return [
//            'Laminas\ApiTools\Autoloader' => [
//                'namespaces' => [
//                    __NAMESPACE__ => __DIR__ . '/src',
//                ],
//            ],
//        ];
//    }

    public function onAuthentication(MvcAuthEvent $e): IdentityInterface
    {
        $tokenService = new TokenService();
        $guest        = new GuestIdentity();

        // Get the Authorization header
        /** @var Request $request */
        $request = $e->getMvcEvent()->getRequest();
        /** @var GenericHeader|null $authHeader */
        $authHeader = $request->getHeader('Authorization');

        // Return guest identity if no Authorization header is sent
        if (!$authHeader) {
            return $guest;
        }

        $token = $authHeader->getFieldValue();

        try {
            /** @var ServiceManager $sm */
            $sm = $e->getMvcEvent()->getApplication()->getServiceManager();

            // Initialize the cache for the JWKs
            $jwkCache = new CacheItemPoolDecorator($sm->get('auth-token-cache'));

            // Decode & validate the token
            $tokenDecoded = $tokenService->validateToken($token, $jwkCache, $sm->get('config')['auth']['jwksUrl']);

            // Find the corresponding user. If the token contains
            if (!is_null($tokenDecoded->getEmail())) {
                $user = UserIdentity::createFromJWT($tokenDecoded);
            } else {
                $user = new MachineUserIdentity();
                $user
                    ->setApplicationId($tokenDecoded->getClientId())
                    ->setScopes($tokenDecoded->getScopes());
            }

            $authenticatedIdentity = new AuthenticatedIdentity($user);

            /** @var AuthenticationService $authService */
            $authService = $sm->get(AuthenticationService::class);
            $authService->getStorage()->write($user);

            return $authenticatedIdentity;
        } catch (InvalidTokenException $e) {
            error_log($e->getMessage());
            return $guest;
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
            return $guest;
        }
    }
}
