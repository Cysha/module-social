<?php

return [

    'backend_sidebar' => [

        'System' => [
            [
                'route'      => 'admin.config.social',
                'text'       => 'Social Manager',
                'icon'       => 'fa-share-alt-square',
                'order'      => 1,
                'permission' => 'manage@social_admin'
            ],
        ]
    ],

];
