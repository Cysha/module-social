<?php

$serializer = new SuperClosure\Serializer;

return [
    'Auth' => [
        'User' => [
            'providers' => $serializer->serialize(function ($self) {
                return $self->hasMany('Cms\Modules\Social\Models\UserProvider');
            }),

            'hasProvider' => $serializer->serialize(function ($self, $provider) {
                if (!$self->providers()->count()) {
                    return false;
                }

                foreach ($self->providers()->get() as $p) {
                    if (strtolower($p->provider) === strtolower($provider)) {
                        return true;
                    }
                }
                return false;
            })
        ],
    ],
];
