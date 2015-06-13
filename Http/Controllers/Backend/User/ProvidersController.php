<?php namespace Cms\Modules\Social\Http\Controllers\Backend\User;

use Cms\Modules\Auth\Http\Controllers\Backend\User\BaseUserController;
use Illuminate\Http\Request;
use Cms\Modules\Auth as Auth;

class ProvidersController extends BaseUserController
{
    public function getForm(Auth\Models\User $user)
    {
        $data = $this->getUserDetails($user);
        $this->theme->breadcrumb()->add('Social Providers', route('admin.user.provider', $user->id));

        return $this->setView('admin.user.providers', $data, 'module');
    }

    public function postForm(Auth\Models\User $user, Request $input)
    {
        $input = $input->only(['']);

        $user->hydrateFromInput($input);

        if ($user->save() === false) {
            return Redirect::back()->withErrors($user->getErrors());
        }

        return Redirect::route('admin.user.password', $user->id)->withInfo('Password Updated');
    }
}
