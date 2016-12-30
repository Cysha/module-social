<?php

namespace Cms\Modules\Social\Providers;

use Cms\Modules\Core\Providers\BaseEventsProvider;

class RegisterSocialitesProvider extends BaseEventsProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
    ];

    /**
     * Register any other events for your application.
     */
    public function boot()
    {
        $this->registerSocialiteProviders();
        parent::boot();
    }

    /**
     * Check to see if we have any installed socialite providers.
     */
    private function registerSocialiteProviders()
    {
        if (!class_exists('SocialiteProviders\Manager\ServiceProvider')) {
            return;
        }
        $file = app('files');
        $path = base_path('vendor/socialiteproviders/');
        if (!$file->exists($path)) {
            return;
        }

        $listen = [];
        $listener = 'SocialiteProviders\Manager\SocialiteWasCalled';
        foreach ($file->Directories($path) as $dir) {
            $dirName = class_basename($dir);
            if ($dirName == 'manager') {
                continue;
            }

            $keys = [
                config('services.'.$dirName.'.client_id', null),
                config('services.'.$dirName.'.client_secret', null),
            ];

            if (in_array(null, $keys)) {
                continue;
            }

            $event = sprintf('SocialiteProviders\%1$s\%1$sExtendSocialite', ucwords($dirName));

            $listen[] = $event;
            \Debug::console([$event, $listener]);
        }

        $this->listen['SocialiteProviders\Manager\SocialiteWasCalled'] = $listen;
    }
}
