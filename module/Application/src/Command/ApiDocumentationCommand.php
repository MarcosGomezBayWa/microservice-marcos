<?php

namespace Application\Command;

use Laminas\Config\Config;
use OpenApi\Generator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:api-documentation',
    description: 'Generate the swagger API documentation.',
    hidden: false
)]
class ApiDocumentationCommand extends Command
{
    private const PROD_API_HOST = 'https://api.baywa-lusy.com';
    private const PROD_TOKEN_URL = 'https://auth.baywa-lusy.com/realms/master/protocol/openid-connect/token';
    private const API_DOC_NAME = 'swagger.json';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_error_handler(function ($severity, $message, $file, $line) {
            throw new \ErrorException($message, $severity, $severity, $file, $line);
        });

        $this->defineApiConstants();

        if (!$doc = $this->generateApi()) {
            return Command::FAILURE;
        }

        $apiPath = getcwd() . '/public/' . self::API_DOC_NAME;
        file_put_contents($apiPath, $doc);

        $output->writeln('<info>Great! API documentation successfully created.</info>');

        return Command::SUCCESS;
    }

    private function defineApiConstants(): void
    {
        $envLocalPath = getcwd() . '/config/autoload/env.local.php';
        $authLocalPath = getcwd() . '/config/autoload/auth.local.php';

        // Prod URLs if one of these files are missing
        if (!file_exists($envLocalPath) || !file_exists($authLocalPath)) {
            define('API_HOST', self::PROD_API_HOST);
            define('TOKEN_URL', self::PROD_TOKEN_URL);
            return;
        }

        // Test config in other cases
        $configReader = new Config([]);
        $configReader
            ->merge(new Config(include $envLocalPath))
            ->merge(new Config(include $authLocalPath));

        define('API_HOST', $configReader->hostname);
        define('TOKEN_URL', $configReader->auth->serverAddress . $configReader->auth->tokenEndpoint);
    }

    private function generateApi(): string
    {
        $modulePath = getcwd() . '/module';
        $baywaLusyPath = getcwd() . '/vendor/baywa-re-lusy/user-management/src/UserManagement';

        $openapi = Generator::scan([$modulePath, $baywaLusyPath]);

        return $openapi->toJson();
    }
}
