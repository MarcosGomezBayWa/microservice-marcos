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
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\ServiceManager\ServiceManager;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.1.0",
    description: "This is the BayWa r.e. Example API documentation. More about BayWa r.e. on https://baywa-re.com/",
    title: "BayWa r.e. Example API",
    contact: new OA\Contact(
        name: "Pascal Paulis",
        email: "pascal.paulis@baywa-re.com"
    )
)]
#[OA\SecurityScheme(
    securityScheme: "api_auth",
    type: "oauth2",
    flows: [
        new OA\Flow(
            tokenUrl: TOKEN_URL,
            flow: "clientCredentials",
            scopes: []
        )
    ]
)]
#[OA\Server(url: API_HOST)]
#[OA\Schema(
    schema: "HalCollectionLinks",
    properties: [
        new OA\Property(
            property: "_links",
            properties: [
                new OA\Property(
                    property: "self",
                    properties: [
                        new OA\Property(property: "href", type: "string")
                    ],
                    type: "object"
                ),
                new OA\Property(
                    property: "first",
                    properties: [
                        new OA\Property(property: "href", type: "string")
                    ],
                    type: "object"
                ),
                new OA\Property(
                    property: "last",
                    properties: [
                        new OA\Property(property: "href", type: "string")
                    ],
                    type: "object"
                )
            ],
            type: "object"
        )
    ]
)]
#[OA\Schema(
    schema: "HalCollectionCounts",
    properties: [
        new OA\Property(property: "page_count", type: "integer", example: 42),
        new OA\Property(property: "page_size", type: "integer", example: 100),
        new OA\Property(property: "total_items", type: "integer", example: 5359),
        new OA\Property(property: "page", type: "integer", example: 3)
    ]
)]
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

        // Nginx runs on port 8090, but docker compose (or Kubernetes) receives requests on port 80 and redirects them
        // to port 8090 internally. However, the Laminas Admin UI generates URLs based on SERVER_PORT in its layout.
        // So, all subsequent requests to static files in the Admin UI will try to load them on port 8090. To prevent
        // this, we reset the port.
        $eventManager->attach(MvcEvent::EVENT_ROUTE, function () {
            $_SERVER['SERVER_PORT'] = '';
        }, 1000);

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

            // Find the corresponding user
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
