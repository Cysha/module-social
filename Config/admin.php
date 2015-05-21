<?php

return [

    'acp_menu' => [

        'System' => [
            [
                'route'      => 'admin.config.social',
                'text'       => 'Social Manager',
                'icon'       => 'fa-share-alt-square',
                'order'      => 5,
                'permission' => 'manage@social_admin'
            ],
        ]
    ],

];
