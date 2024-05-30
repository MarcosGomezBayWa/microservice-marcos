<?php

namespace MyApi\V1\Rpc\Healthcheck;

use Psr\Container\ContainerInterface;

class HealthcheckControllerFactory
{
    public function __invoke(ContainerInterface $container): HealthcheckController
    {
        return new HealthcheckController();
    }
}
