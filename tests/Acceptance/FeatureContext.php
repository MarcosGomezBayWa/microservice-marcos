<?php

namespace Test\Acceptance;

use BayWaReLusy\BehatContext\AuthContext\AuthContextAwareInterface;
use BayWaReLusy\BehatContext\AuthContext\AuthContextAwareTrait;
use BayWaReLusy\BehatContext\ConsoleContext\ConsoleContextAwareInterface;
use BayWaReLusy\BehatContext\ConsoleContext\ConsoleContextAwareTrait;
use BayWaReLusy\BehatContext\HalContext\HalContextAwareInterface;
use BayWaReLusy\BehatContext\HalContext\HalContextAwareTrait;
use BayWaReLusy\BehatContext\SqsContext\SqsContextAwareInterface;
use BayWaReLusy\BehatContext\SqsContext\SqsContextAwareTrait;
use BayWaReLusy\QueueTools\Adapter\AwsSqsAdapter;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManager;
use Exception;
use Laminas\Mvc\Application as ZfApplication;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Stdlib\ArrayUtils;
use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use BayWaReLusy\QueueTools\QueueTools;
use BayWaReLusy\QueueTools\QueueService;
use BayWaReLusy\BehatContext\Auth0Context\MachineToMachineCredentials;
use BayWaReLusy\BehatContext\Auth0Context\UserCredentials;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements
    Context,
    HalContextAwareInterface,
    ConsoleContextAwareInterface,
    AuthContextAwareInterface,
    SqsContextAwareInterface
{
    use HalContextAwareTrait;
    use ConsoleContextAwareTrait;
    use AuthContextAwareTrait;
    use SqsContextAwareTrait;

    /** @var ZfApplication */
    protected ZfApplication $apiToolsApplication;

    protected array $placeholders = [];

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        require_once __DIR__ . '/../../vendor/autoload.php';
        date_default_timezone_set('UTC');
        ini_set('memory_limit', '-1');

        $appConfig = include __DIR__ . '/../../config/application.config.php';

        // Load development config to avoid enabling the config & module cache
        if (file_exists(__DIR__ . '/../../config/development.config.php')) {
            $appConfig = ArrayUtils::merge($appConfig, include __DIR__ . '/../../config/development.config.php');
        }

        $this->apiToolsApplication = ZfApplication::init($appConfig);
    }

    public function getServiceManager(): ServiceManager
    {
        return $this->apiToolsApplication->getServiceManager();
    }

    /**
     * @return EntityManager
     * @throws Exception
     */
    public function getEntityManager(): EntityManager
    {
        try {
            return $this->getServiceManager()->get(EntityManager::class);
        } catch (\Throwable $e) {
            throw new Exception(sprintf("Couldn't retrieve Entity Manager: %s", $e->getMessage()));
        }
    }

    /**
     * @param BeforeScenarioScope $scope
     * @throws Exception
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        if (!is_string(getenv('APACHE_HOSTNAME'))) {
            throw new Exception('Env var APACHE_HOSTNAME must be set.');
        }

        $this->gatherConsoleContext($scope);

        $this->gatherHalContext($scope);
        $this->getHalContext()
            ->setJsonFilesPath((string)realpath(__DIR__ . '/../../features/_files'))
            ->setBaseUrl(getenv('APACHE_HOSTNAME'));

        $this->gatherAuthContext($scope);
        $this->getAuthContext()
            ->setHalContext($this->getHalContext())
            ->setServerAddress($this->getServiceManager()->get('config')['auth']['serverAddress'])
            ->setTokenEndpoint($this->getServiceManager()->get('config')['auth']['tokenEndpoint'])
//            ->addMachineToMachineCredentials(new MachineToMachineCredentials(
//                '<client name>',
//                '<client ID>',
//                '<client secret>'
//            ))
            ->addUserCredentials(new \BayWaReLusy\BehatContext\AuthContext\UserCredentials(
                'pascal.paulis.testing',
                'Azerty!!1234',
                'tms-frontend'
            ));

        $adapter = new AwsSqsAdapter(
            $this->getServiceManager()->get('config')['aws']['region'],
            $this->getServiceManager()->get('config')['aws']['credentials']['key'],
            $this->getServiceManager()->get('config')['aws']['credentials']['secret']
        );

        $this->gatherSqsContext($scope);
        $this->getSqsContext()
            ->setAwsRegion($this->getServiceManager()->get('config')['aws']['region'])
            ->setAwsKey($this->getServiceManager()->get('config')['aws']['credentials']['key'])
            ->setAwsSecret($this->getServiceManager()->get('config')['aws']['credentials']['secret'])
            ->setQueueService(new QueueService($adapter))
//            ->addQueue(new QueueUrl(
//                '<queue name>',
//                $this->getServiceManager()->get('config')['queue']['<queue name>']['queueUrl']
//            ))
            ;
    }

    /**
     * @BeforeScenario
     * @throws Exception
     */
    public function purgeDatabaseAndCache(): void
    {
        // Purge Database
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();

        // Clear all queues
        $this->sqsContext->clearAllQueues();
    }
}
