<?php

return [
    'Auth' => [
        'User' => [
            'providers' => function ($self) {
                return $self->hasMany('Cms\Modules\Social\Models\UserProvider');
            },

            'hasProvider' => function ($self, $provider) {
                if (!$self->providers()->count()) {
                    return false;
                }

                foreach ($self->providers()->get() as $p) {
                    if (strtolower($p->provider) === strtolower($provider)) {
                        return true;
                    }
                }
                return false;
            }
        ],
    ],
];
