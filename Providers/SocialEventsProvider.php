<?php namespace Cms\Modules\Social\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Cms\Modules\Core\Providers\BaseEventsProvider;
use Cache;

class SocialEventsProvider extends BaseEventsProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Cms\Modules\Admin\Events\GotDatatableConfig' => [
            'Cms\Modules\Social\Events\Handlers\ManipulateUserDatatable',
        ],
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
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        $this->registerSocialiteProviders();
    }

    /**
     * Check to see if we have any installed socialite providers
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
        foreach ($file->Directories($path) as $dir) {
            if (class_basename($dir) == 'manager') {
                continue;
            }

            $listen[] = sprintf('SocialiteProviders\%1$s\%1$sExtendSocialite@handle', ucwords(class_basename($dir)));
        }

        $this->listen['SocialiteProviders\Manager\SocialiteWasCalled'] = $listen;
    }
}
