<?php

return [
    'Auth' => [
        'User' => [
            'providers' => function ($self) {
                return $self->hasMany('Cms\Modules\Social\Models\UserProvider');
            },

            'hasProvider' => function ($self, $provider) {
                foreach ($self->providers() as $p) {
                    if ($p->provider === $provider) {
                        return true;
                    }
                }
                return false;
            }
        ],
    ],
];
