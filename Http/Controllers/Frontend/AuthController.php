<?php namespace Cms\Modules\Social\Http\Controllers\Frontend;

use Cms\Modules\Core\Http\Controllers\BaseModuleController;
use Cms\Modules\Social\Services\Social;
use Illuminate\Http\Request;

class AuthController extends BaseModuleController
{
    public $layout = '2-column-left';

    protected $social;

    public function __construct(Social $social)
    {
        $this->social = $social;

        $this->_setDependencies(
            app('Teepluss\Theme\Contracts\Theme'),
            app('Illuminate\Filesystem\Filesystem')
        );

        $this->middleware('guest', ['except' => ['getLogout', 'loginThirdParty']]);
    }

    /**
     * Setup the Socialite login procedure
     */
    public function loginThirdParty(Request $request, $provider)
    {
        return $this->social->loginThirdParty($request->all(), $provider);
    }

    /**
     * Render the login form.
     */
    public function getLogin()
    {
        // grab the configured providers
        $configuredProviders = $this->social->getConfiguredProviders();

        // if we dont have any render the original form
        if (!count($configuredProviders)) {
            return $this->setView('partials.core.login', [], 'theme');
        }

        // if we have some, then throw our new one into the mix
        return $this->setView('core.login', [
            'socialiteProviders' => $this->social->getProviders(),
            'configuredProviders' => $configuredProviders,
        ]);
    }
}
