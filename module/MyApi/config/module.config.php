<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'MyApi\\V1\\Rest\\MyService\\MyServiceResource' => 'MyApi\\V1\\Rest\\MyService\\MyServiceResourceFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'my-api.rest.my-service' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/my-service[/:my_service_id]',
                    'defaults' => array(
                        'controller' => 'MyApi\\V1\\Rest\\MyService\\Controller',
                    ),
                ),
            ),
            'my-api.rpc.healthcheck' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/healthcheck',
                    'defaults' => array(
                        'controller' => 'MyApi\\V1\\Rpc\\Healthcheck\\Controller',
                        'action' => 'healthcheck',
                    ),
                ),
            ),
            'my-api.rpc.is-ready' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/is-ready',
                    'defaults' => array(
                        'controller' => 'MyApi\\V1\\Rpc\\IsReady\\Controller',
                        'action' => 'isReady',
                    ),
                ),
            ),
        ),
    ),
    'api-tools-versioning' => array(
        'uri' => array(
            0 => 'my-api.rest.my-service',
            1 => 'my-api.rpc.healthcheck',
            2 => 'my-api.rpc.is-ready',
        ),
    ),
    'api-tools-rest' => array(
        'MyApi\\V1\\Rest\\MyService\\Controller' => array(
            'listener' => 'MyApi\\V1\\Rest\\MyService\\MyServiceResource',
            'route_name' => 'my-api.rest.my-service',
            'route_identifier_name' => 'my_service_id',
            'collection_name' => 'myService',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'MyApi\\V1\\Rest\\MyService\\MyServiceEntity',
            'collection_class' => 'MyApi\\V1\\Rest\\MyService\\MyServiceCollection',
            'service_name' => 'myService',
        ),
    ),
    'api-tools-content-negotiation' => array(
        'controllers' => array(
            'MyApi\\V1\\Rest\\MyService\\Controller' => 'HalJson',
            'MyApi\\V1\\Rpc\\Healthcheck\\Controller' => 'Json',
            'MyApi\\V1\\Rpc\\IsReady\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'MyApi\\V1\\Rest\\MyService\\Controller' => array(
                0 => 'application/vnd.my-api.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'MyApi\\V1\\Rpc\\Healthcheck\\Controller' => array(
                0 => 'application/vnd.my-api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'MyApi\\V1\\Rpc\\IsReady\\Controller' => array(
                0 => 'application/vnd.my-api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
        ),
        'content_type_whitelist' => array(
            'MyApi\\V1\\Rest\\MyService\\Controller' => array(
                0 => 'application/vnd.my-api.v1+json',
                1 => 'application/json',
            ),
            'MyApi\\V1\\Rpc\\Healthcheck\\Controller' => array(
                0 => 'application/vnd.my-api.v1+json',
                1 => 'application/json',
            ),
            'MyApi\\V1\\Rpc\\IsReady\\Controller' => array(
                0 => 'application/vnd.my-api.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'api-tools-hal' => array(
        'metadata_map' => array(
            'Doctrine\\ORM\\PersistentCollection' => array(
                'hydrator' => 'ArraySerializableHydrator',
                'entity_identifier_name' => 'id',
                'route_identifier_name' => 'id',
                'route_name' => 'doctrine-orm-persistent-collection',
                'isCollection' => true,
            ),
            'Doctrine\\Common\\Collections\\ArrayCollection' => array(
                'hydrator' => 'ArraySerializableHydrator',
                'entity_identifier_name' => 'id',
                'route_identifier_name' => 'id',
                'route_name' => 'doctrine-common-collections-array-collection',
                'isCollection' => true,
            ),
            'Doctrine\\ORM\\LazyCriteriaCollection' => array(
                'hydrator' => 'ArraySerializableHydrator',
                'entity_identifier_name' => 'id',
                'route_identifier_name' => 'id',
                'route_name' => 'doctrine-orm-lazy-criteria-collection',
                'isCollection' => true,
            ),
            'MyApi\\V1\\Rest\\MyService\\MyServiceEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'my-api.rest.my-service',
                'route_identifier_name' => 'my_service_id',
                'hydrator' => 'Laminas\\Hydrator\\ArraySerializableHydrator',
            ),
            'MyApi\\V1\\Rest\\MyService\\MyServiceCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'my-api.rest.my-service',
                'route_identifier_name' => 'my_service_id',
                'is_collection' => true,
            ),
        ),
    ),
    'api-tools-mvc-auth' => array(
        'authorization' => array(
            'MyApi\\V1\\Rest\\MyService\\Controller' => array(
                'collection' => array(
                    'GET' => true,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
                'entity' => array(
                    'GET' => true,
                    'POST' => false,
                    'PUT' => true,
                    'PATCH' => true,
                    'DELETE' => true,
                ),
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'MyApi\\V1\\Rpc\\Healthcheck\\Controller' => 'MyApi\\V1\\Rpc\\Healthcheck\\HealthcheckControllerFactory',
            'MyApi\\V1\\Rpc\\IsReady\\Controller' => 'MyApi\\V1\\Rpc\\IsReady\\IsReadyControllerFactory',
        ),
    ),
    'api-tools-rpc' => array(
        'MyApi\\V1\\Rpc\\Healthcheck\\Controller' => array(
            'service_name' => 'Healthcheck',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'my-api.rpc.healthcheck',
        ),
        'MyApi\\V1\\Rpc\\IsReady\\Controller' => array(
            'service_name' => 'IsReady',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'my-api.rpc.is-ready',
        ),
    ),
);
