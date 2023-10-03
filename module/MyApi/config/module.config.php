<?php
return [
    'service_manager' => [
        'factories' => [
            \MyApi\V1\Rest\MyService\MyServiceResource::class => \MyApi\V1\Rest\MyService\MyServiceResourceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'my-api.rest.my-service' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/my-service[/:my_service_id]',
                    'defaults' => [
                        'controller' => 'MyApi\\V1\\Rest\\MyService\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            0 => 'my-api.rest.my-service',
        ],
    ],
    'api-tools-rest' => [
        'MyApi\\V1\\Rest\\MyService\\Controller' => [
            'listener' => \MyApi\V1\Rest\MyService\MyServiceResource::class,
            'route_name' => 'my-api.rest.my-service',
            'route_identifier_name' => 'my_service_id',
            'collection_name' => 'myService',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \MyApi\V1\Rest\MyService\MyServiceEntity::class,
            'collection_class' => \MyApi\V1\Rest\MyService\MyServiceCollection::class,
            'service_name' => 'myService',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers' => [
            'MyApi\\V1\\Rest\\MyService\\Controller' => 'HalJson',
        ],
        'accept_whitelist' => [
            'MyApi\\V1\\Rest\\MyService\\Controller' => [
                0 => 'application/vnd.my-api.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'MyApi\\V1\\Rest\\MyService\\Controller' => [
                0 => 'application/vnd.my-api.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'api-tools-hal' => [
        'metadata_map' => [
            \MyApi\V1\Rest\MyService\MyServiceEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'my-api.rest.my-service',
                'route_identifier_name' => 'my_service_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \MyApi\V1\Rest\MyService\MyServiceCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'my-api.rest.my-service',
                'route_identifier_name' => 'my_service_id',
                'is_collection' => true,
            ],
        ],
    ],
    'api-tools-mvc-auth' => [
        'authorization' => [
            'MyApi\\V1\\Rest\\MyService\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => true,
                    'PATCH' => true,
                    'DELETE' => true,
                ],
            ],
        ],
    ],
];
