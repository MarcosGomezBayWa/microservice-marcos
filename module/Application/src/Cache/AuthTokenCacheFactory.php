<?php

namespace Application\Cache;

use Laminas\Cache\Storage\Adapter\Apcu;
use Laminas\Cache\Storage\Plugin\ExceptionHandler;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class AuthTokenCacheFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $cache  = new Apcu();
        $cache->getOptions()->setTtl(604800);

        $plugin = new ExceptionHandler();
        $plugin->getOptions()->setThrowExceptions(false);
        $cache->addPlugin($plugin);

        return $cache;
    }
}
