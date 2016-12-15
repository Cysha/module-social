<?php

namespace Cms\Modules\Social\Providers;

use Cms\Modules\Social\Models\UserProvider;
use Illuminate\Support\ServiceProvider;

class SocialModelsProvider extends ServiceProvider
{
    /**
     * Register any other events for your application.
     */
    public function boot()
    {
        $model = config('cms.auth.config.user_model');

        $model::macro('providers', function () {
            return $this->hasMany(UserProvider::class);
        });

        $model::macro('hasProvider', function ($provider) {
            if (!$this->providers()->count()) {
                return false;
            }

            foreach ($this->providers()->get() as $p) {
                if (strtolower($p->provider) === strtolower($provider)) {
                    return true;
                }
            }

            return false;
        });
    }
}
