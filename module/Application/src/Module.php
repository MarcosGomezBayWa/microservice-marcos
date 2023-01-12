<?php

declare(strict_types=1);

namespace Application;

use Laminas\Config\Config;

class Module
{
    /**
     * @return array<string,mixed>
     */
    public function getConfig()
    {
        $config = new Config(include __DIR__ . '/../config/module.config.php');
        $config->merge(new Config(include __DIR__ . '/../config/services.php'));

        return $config;
    }
}
