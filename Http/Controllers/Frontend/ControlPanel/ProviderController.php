<?php

namespace Cms\Modules\Social\Http\Controllers\Frontend\ControlPanel;

use Cms\Modules\Auth\Http\Controllers\Frontend\ControlPanel\BaseController;
use Cms\Modules\Social\Services\Social;
use Illuminate\Support\Facades\Auth;

class ProviderController extends BaseController
{
    public function getForm(Social $social)
    {
        $data = $this->getUserDetails();
        $this->theme->breadcrumb()->add('Social Provider', route('pxcms.user.provider_settings'));

        $data['socialiteProviders'] = $social->getProviders();
        $data['installedProviders'] = $social->getInstalledProviders();
        $data['configuredProviders'] = $social->getConfiguredProviders();
        $data['user'] = Auth::user();

        return $this->setView('controlpanel.providers', $data);
    }

    public function postForm()
    {
    }
}
