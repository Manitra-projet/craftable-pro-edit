<?php
// config for Brackets/CraftablePro
use CustomPackages\CustomApp\Translations\Scanners\External\JsonScanner;
use CustomPackages\CustomApp\Translations\Scanners\External\PhpArrayScanner;
use CustomPackages\CustomApp\Translations\Scanners\Internal\JsScanner;
use CustomPackages\CustomApp\Translations\Scanners\Internal\PhpScanner;

return [
    // default media disk name
    'default_media_disk_name' => 'media',

    // define if email must be verified in order to be able to log in
    'require_email_verified' => true,

    // define or only active users can log in
    'allow_only_active_users_login' => true,

    // define if track user last activity timestamp
    'track_user_last_active_time' => true,

    'handle-inertia-request-class' => App\Http\Middleware\CustomAppHandleInertiaRequests::class,

    'self_registration' => [
        // define if users can self register into craftable pro interface
        'enabled' => false,

        // and if enabled, then which role(s) they should have assigned by default. Use role names here.
        // It can be a string for one role or an array for multiple roles.
        'default_role' => 'Guest',
    ],

    'translations' => [
        'scan' => [
            PHPScanner::class => [
                'paths' => [
                    base_path('custom-packages/custom-app/src/Http/Controllers'),
                    resource_path('views')
                ]
            ],
            JsScanner::class => [
                'paths' => [
                    base_path('custom-packages/custom-app/resources/js'),
                    resource_path('js'),
                ],
            ]
        ],

        //-----------------------------------------------------
        // Example of external language file
        //-----------------------------------------------------

        'external' => [
            [
                'group' => 'permissions',
                'scan' => [
                    JsonScanner::class => [
                        'paths' => [
                            resource_path('translations/permissions'),
                        ]
                    ],
                ]
            ],
            [
                'group' => 'locales',
                'scan' => [
                    JsonScanner::class => [
                        'paths' => [
                            resource_path('translations/locales'),
                        ]
                    ],
                ]
            ],
            [
                'scan' => [
                    PhpArrayScanner::class => [
                        'paths' => [
                            lang_path('/'),
                        ]
                    ],
                ]
            ]
        ],

        //-----------------------------------------------------
        // Example of publishing of json file with translations
        //-----------------------------------------------------

        'publish' => [
            'custom-app' => [
                'groups' => ['custom-app', 'permissions', 'locales'],
                'path' => public_path('lang/'),
            ],
        ]
    ]
];
