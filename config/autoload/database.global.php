<?php

use Application\Cache\DoctrineCacheFactory;

return [
    'service_manager' =>
        [
            'factories' =>
                [
                    'doctrine.cache.zf' => DoctrineCacheFactory::class,
                ],
        ],
    'doctrine' =>
        [
            'migrations_configuration' =>
                [
                    'orm_default' =>
                        [
                            'migrations_paths' =>
                                [
                                    'DoctrineORMModule\Migrations' => 'data/DoctrineORMModule/Migrations',
                                ],
                            'all_or_nothing'          => true,
                            'check_database_platform' => true,
                            'organize_migrations'     => 'year',
                            'table_storage'           =>
                                [
                                    'table_name'                 => 'migrations',
                                    'version_column_name'        => 'version',
                                    'version_column_length'      => 1024,
                                    'executed_at_column_name'    => 'executed_at',
                                    'execution_time_column_name' => 'execution_time',
                                ],
                        ],
                ],
            'driver' =>
                [
                    // defines an annotation driver with two paths, and names it `doctrine_driver`
                    'doctrine_driver' =>
                        [
                            'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                            'cache' => 'zf',
                            'paths' =>
                                [
                                    __DIR__ . '/../../module/Application/src',
                                    __DIR__ . '/../../module/MyApi/src/V1/Rest',
                                    // __DIR__ . '/../../module/MyApi/src/V1/Rpc',
                                ],
                        ],
                    // default metadata driver, aggregates all other drivers into a single one.
                    // Override `orm_default` only if you know what you're doing
                    'orm_default' =>
                        [
                            'drivers' =>
                                [
                                    // register `doctrine_driver` for any entity under namespace `My\Namespace`
                                    'Application'   => 'doctrine_driver',
                                    'MyApi\V1\Rest' => 'doctrine_driver',
                                    'MyApi\V1\Rpc'  => 'doctrine_driver',
                                ],
                        ],
                ],
            'configuration' =>
                [
                    'orm_default' =>
                        [
                            'generate_proxies' => true,
                            'driver'           => 'orm_default',
                            'naming_strategy'  => 'doctrine.naming_strategy.underscore',
                            'types'            => [],
                        ],
                ],
        ],
];
