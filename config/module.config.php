<?php
return [

    'authogility' => [

    ],

    'zf-oauth2' => [
        // https://apigility.org/documentation/auth/authentication-oauth2
        'options' => [
            'always_issue_new_refresh_token' => true, // zf2 default is false
            // 'refresh_token_lifetime' => (default is 1209600, equal to 14 days)
        ],
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

];