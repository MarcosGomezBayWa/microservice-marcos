<?php

namespace Application\Authentication;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Authentication\Storage\NonPersistent as NonPersistentStorage;
use Laminas\Authentication\AuthenticationService;
use Psr\Container\ContainerInterface;

class AuthenticationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new AuthenticationService(new NonPersistentStorage());
    }
}
