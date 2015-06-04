<?php namespace Cms\Modules\Social\Providers;

use Cms\Modules\Core\Providers\BaseModuleProvider;
use Illuminate\Foundation\AliasLoader;
use Config;
use Request;

class SocialModuleServiceProvider extends BaseModuleProvider
{

    /**
     * Register the defined middleware.
     *
     * @var array
     */
    protected $middleware = [
        'Social' => [
        ],
    ];

    /**
     * The commands to register.
     *
     * @var array
     */
    protected $commands = [
        'Social' => [
        ],
    ];

    /**
     * Register repository bindings to the IoC
     *
     * @var array
     */
    protected $bindings = [
    ];

    /**
     * Register Auth related stuffs
     */
    public function register()
    {
        parent::register();

        $this->checkForSocialite();
    }

    public function boot()
    {
        parent::boot();
    }

    private function checkForSocialite()
    {
        // if socialite or the socialiteproviders package is installed, load em
        $loadSocialite = false;
        if (class_exists('SocialiteProviders\Manager\ServiceProvider')) {
            $loadSocialite = true;
            $this->app->register('SocialiteProviders\Manager\ServiceProvider');

        } elseif (class_exists('Laravel\Socialite\SocialiteServiceProvider')) {
            $loadSocialite = true;
            $this->app->register('Laravel\Socialite\SocialiteServiceProvider');
        }

        if ($loadSocialite === true) {
            AliasLoader::getInstance()->alias('Socialite', 'Laravel\Socialite\Facades\Socialite');
        }
    }


}
