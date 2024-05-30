<?php

namespace MyApi\V1\Rpc\IsReady;

use Psr\Container\ContainerInterface;

class IsReadyControllerFactory
{
    public function __invoke(ContainerInterface $container): IsReadyController
    {
        return new IsReadyController();
    }
}
