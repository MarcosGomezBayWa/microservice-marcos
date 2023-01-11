BayWa r.e. Microservice Skeleton
================================

# Setup

1. Copy the entire repository to your project :
   1. Clone this repository
   2. `cd microservice-skeleton`
   3. Mirror Push to your new repository : `git push --mirror https://github.com/baywa-re-lusy/<your new repository>.git`
2. Add an API & Database container to the local docker compose setup.
2. Run `./composer.phar install`
3. Copy a file `local-conf.ini.dist` to `local-conf.ini` and complete the missing values
4. Run `vendor/bin/phing init -propertyfile local-conf.ini`
5. Search all occurences of the term *Microservice* (`README.md`, `build.xml`, `composer.json`, etc.) and replace them with the name of your project.

# Authentication

After having created your API in the ApiTools Admin interface, open the `Module.php` file of the newly created module and add the following :

```php
use Application\User\UserMapper;
use BayWaReLusy\JwtAuthentication\InvalidTokenException;
use BayWaReLusy\JwtAuthentication\TokenService;
use BayWaReLusy\UserManagement\MachineUserEntity;
use Laminas\ApiTools\MvcAuth\Identity\AuthenticatedIdentity;
use Laminas\ApiTools\MvcAuth\Identity\GuestIdentity;
use Laminas\ApiTools\MvcAuth\Identity\IdentityInterface;
use Laminas\ApiTools\MvcAuth\MvcAuthEvent;
use Laminas\Authentication\AuthenticationService;
use Laminas\Cache\Psr\CacheItemPool\CacheItemPoolDecorator;
use Laminas\Http\Header\GenericHeader;
use Laminas\Http\Request;
use Laminas\ServiceManager\ServiceManager;

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
         if (!is_null($tokenDecoded->getEmail()) || str_starts_with($tokenDecoded->getSub(), 'auth0|')) {
             /** @var UserMapper $userMapper */
             $userMapper = $sm->get(UserMapper::class);
             $user       = $userMapper->find($tokenDecoded->getSub());
             $user->setPermissions($tokenDecoded->getScope());
         } else {
             $user = new MachineUserEntity();
             $user
                 ->setApplicationId($tokenDecoded->getAzp())
                 ->setPermissions($tokenDecoded->getScope());
         }

         // Set the user's identity
         if ($user) {
             // @phpstan-ignore-next-line
             $authenticatedIdentity = new AuthenticatedIdentity($user);

             /** @var AuthenticationService $authService */
             $authService = $sm->get(AuthenticationService::class);
             $authService->getStorage()->write($user);

             return $authenticatedIdentity;
         } else {
             return $guest;
         }
     } catch (InvalidTokenException $e) {
         error_log($e->getMessage());
         return $guest;
     } catch (\Throwable $e) {
         error_log($e->getMessage());
         error_log($e->getTraceAsString());
         return $guest;
     }
 }
```
