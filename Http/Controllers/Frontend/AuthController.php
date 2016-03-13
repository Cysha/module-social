<?php

namespace Cms\Modules\Social\Http\Controllers\Frontend;

use Cms\Modules\Core\Http\Controllers\BaseFrontendController;
use Cms\Modules\Social\Services\Social;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseFrontendController
{
    public $layout = '2-column-left';

    protected $social;

    public function __construct(Social $social)
    {
        $this->social = $social;

        $this->setDependencies(
            app('Teepluss\Theme\Contracts\Theme'),
            app('Illuminate\Filesystem\Filesystem')
        );

        $this->middleware('guest', ['except' => ['getLogout', 'loginThirdParty', 'removeProvider']]);
    }

    /**
     * Setup the Socialite login procedure.
     */
    public function loginThirdParty(Request $request, $provider)
    {
        return $this->social->loginThirdParty($request->all(), $provider);
    }

    public function removeProvider($provider)
    {
        $user_id = Auth::id();

        if (!$this->social->removeProvider($user_id, $provider)) {
            return redirect()->back()->withError(sprintf('%s was not removed successfully', ucwords($provider)));
        }

        return redirect()->back()->withInfo(sprintf('%s removed successfully', ucwords($provider)));
    }

    /**
     * Render the login form.
     */
    public function getLogin()
    {
        $this->setLayout('1-column');

        // grab the configured providers
        $configuredProviders = $this->social->getConfiguredProviders();

        // if we dont have any render the original form
        if (!count($configuredProviders)) {
            return $this->setView('partials.core.login', [], 'theme');
        }

        // if we have some, then throw our new one into the mix
        return $this->setView('core.social-login', [
            'socialiteProviders' => $this->social->getProviders(),
            'configuredProviders' => $configuredProviders,
        ]);
    }
}
