<?php

namespace Cms\Modules\Social\Http\Controllers\Backend\User;

use Cms\Modules\Auth\Http\Controllers\Backend\User\BaseUserController;
use Cms\Modules\Social\Services\Social;
use Cms\Modules\Auth as Auth;

class ProvidersController extends BaseUserController
{
    public function getForm(Auth\Models\User $user)
    {
        $data = $this->getUserDetails($user);
        $this->theme->breadcrumb()->add('Social Providers', route('admin.user.provider', $user->id));

        return $this->setView('admin.user.providers', $data, 'module');
    }

    public function removeProvider(Auth\Models\User $user, $provider, Social $social)
    {
        $user_id = $user->id;

        if (!$social->removeProvider($user_id, $provider)) {
            return redirect()->back()->withError(sprintf('%s was not removed successfully', ucwords($provider)));
        }

        return redirect()->back()->withInfo(sprintf('%s removed successfully', ucwords($provider)));
    }
}
