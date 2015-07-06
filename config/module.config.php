<?php
return [

    'authogility' => [

    ],
    
    'service_manager' => [
        'abstract_factories' => [
            'AclMan\Service\ServiceFactory',
            'AclMan\Storage\StorageFactory',
        ],
        'factories' => [
            'AclMan\Assertion\AssertionManager' => 'AclMan\Assertion\AssertionManagerFactory',
        ]
    ],

    'zf-mvc-auth' => [
        'authorization' => [
            'deny_by_default' => true,
            'ZF\OAuth2\Controller\Auth' => [
                'actions' => [
                    'token' => [
                        'POST' => false, // To enable the POST method in authentication
                    ],
                ],
            ],
        ]
    ],

    'aclman_services' => [
        'Authogility\MvcAuth\Authorization\AclManager' => [
            'storage' => 'Authogility\MvcAuth\Authorization\AclManager\Storage',
            'plugin_manager' => 'AclMan\Assertion\AssertionManager',
            'allow_not_found_resource' => false,
        ],
    ],

    'aclman_storage' => [
        'Authogility\MvcAuth\Authorization\AclManager\Storage' => [
         ],
    ],

];