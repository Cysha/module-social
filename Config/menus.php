<?php

return [

    'backend_sidebar' => [

        'Site Management' => [
            'children' => [
                [
                    'route'      => 'admin.config.social',
                    'text'       => 'Social Manager',
                    'icon'       => 'fa-share-alt-square',
                    'order'      => 10,
                    'permission' => 'manage@social_admin'
                ],
            ],
        ]
    ],

    'backend_user_menu' => [
        [
            'route'      => ['admin.user.provider', ['auth_user_id' => 'segment:3']],
            'text'       => 'Social Providers',
            'icon'       => 'fa-share-alt-square',
            'order'      => 3,
            'permission' => 'manage.update@auth_user'
        ],
    ],


    'frontend_user_controlpanel' => [
        [
            'route'      => 'pxcms.user.provider_settings',
            'text'       => 'Social Providers',
            'icon'       => 'fa-share-alt-square',
            'order'      => 4,
            'permission' => null
        ],
    ],

];
