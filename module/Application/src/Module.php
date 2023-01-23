<?php

declare(strict_types=1);

namespace Application;

use Laminas\Config\Config;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        $config = new Config(include __DIR__ . '/../config/module.config.php');
        $config->merge(new Config(include __DIR__ . '/../config/services.php'));

        return $config;
    }
}
