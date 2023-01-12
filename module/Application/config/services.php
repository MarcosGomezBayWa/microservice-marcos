<?php

use Application\Authentication\AuthenticationServiceFactory;
use Application\Cache\AuthTokenCacheFactory;
use Laminas\Authentication\AuthenticationService;

return
    [
        'service_manager' =>
            [
                'invokables' =>
                    [
                    ],
                'factories' =>
                    [
                        'auth-token-cache'           => AuthTokenCacheFactory::class,
                        AuthenticationService::class => AuthenticationServiceFactory::class,
                    ],
            ],
    ];
