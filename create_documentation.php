<?php

use Laminas\Config\Config;

set_error_handler(function ($severity, $message, $file, $line) {
    throw new \ErrorException($message, $severity, $severity, $file, $line);
});

chdir(dirname(__DIR__));
require_once __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('UTC');
ini_set('memory_limit', '-1');

$configReader = new Config([]);

if (
    file_exists(__DIR__ . '/config/autoload/env.local.php') &&
    file_exists(__DIR__ . '/config/autoload/auth.local.php')
) {
    $configReader
        ->merge(new Config(include __DIR__ . '/config/autoload/env.local.php'))
        ->merge(new Config(include __DIR__ . '/config/autoload/auth.local.php'));
    define('API_HOST', $configReader->hostname);
    define('TOKEN_URL', $configReader->auth->serverAddress . $configReader->auth->tokenEndpoint);
} else {
    define('API_HOST', 'https://api.baywa-lusy.com');
    define('TOKEN_URL', 'https://auth.baywa-lusy.com/realms/master/protocol/openid-connect/token');
}

$openapi = \OpenApi\Generator::scan([
    __DIR__ . '/module',
    __DIR__ . '/vendor/baywa-re-lusy/user-management/src/UserManagement'
]);

if (!$doc = $openapi->toJson()) {
    die(255);
}

file_put_contents(__DIR__ . '/public/swagger.json', $doc);
