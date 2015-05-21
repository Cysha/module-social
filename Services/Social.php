<?php namespace Cms\Modules\Social\Services;

use Illuminate\Contracts\Auth\Guard;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Cms\Modules\Auth\Repositories\User\RepositoryInterface as UserRepository;
use Cms\Modules\Social\Models\UserProvider;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Social
{
    /**
     * @var Socialite
     */
    private $socialite;
    /**
     * @var Guard
     */
    private $auth;
    /**
     * @var UserRepository
     */
    private $user;

    /**
     * @param Socialite $socialite
     * @param Guard $auth
     * @param UserRepository $user
     */
    public function __construct(Socialite $socialite, Guard $auth, UserRepository $user)
    {
        $this->socialite = $socialite;
        $this->user = $user;
        $this->auth = $auth;
    }

    /**
     * Run the process for logging in via Socialite
     */
    public function loginThirdParty($request, $provider)
    {
        if (!$request) {
            return $this->getAuthorizationFirst($provider);
        }
        // grab the user
        $user = $this->getOrCreateUser($provider);

        // log the user in & fire the logged in event
        $this->auth->login($user, true);
        event(new \Cms\Modules\Auth\Events\UserHasLoggedIn(\Auth::user()->id));

        return redirect()->intended(route(config('cms.auth.paths.redirect_login', 'pxcms.pages.home')));
    }

    /**
     * Check if the user is already in the db, if not create it
     */
    private function getOrCreateUser($provider)
    {
        $socialiteUser = $this->getSocialUser($provider);

        try {
            $user = $this->user->where('email', $socialiteUser->email)->first();
        } catch(ModelNotFoundException $e) {
            $details = [
                'username'    => $socialiteUser->nickname,
                'email'       => $socialiteUser->email,
                'avatar'      => $socialiteUser->avatar,
            ];

            if (empty($socialiteUser->nickname)) {
                $details['use_nick'] = 1;
                list($details['first_name'], $details['last_name']) = explode(' ', $socialiteUser->name);
            }

            $user = $this->user->createWithRoles($details, config('cms.auth.config.users.default_user_group'), true);
        }

        if (!$user->hasProvider($provider)) {
            with(new UserProvider)->fill([
                'avatar'      => $socialiteUser->avatar,
                'user_id'     => $user->id,
                'provider'    => $provider,
                'provider_id' => $socialiteUser->id,
            ])->save();
        }

        return $user;
    }

    /**
     * If we need authorization, redirect!
     */
    private function getAuthorizationFirst($provider)
    {
        return $this->socialite->driver($provider)->redirect();
    }

    /**
     * Get the user details from socialite
     */
    private function getSocialUser($provider)
    {
        return $this->socialite->driver($provider)->user();
    }


    /**
     * Grab the list of providers
     *
     * @return array
     */
    public function getProviders()
    {
        return config('cms.social.providers', []);
    }

    /**
     * Grab a list of the installed Providers
     *
     * @return array
     */
    public function getInstalledProviders()
    {
        $path = base_path('vendor/socialiteproviders/');
        $file = app('files');

        $socialiteProviders = ['facebook', 'twitter', 'google', 'github'];
        if ($file->exists($path)) {
            foreach ($file->Directories($path) as $dir) {
                $dir = class_basename($dir);
                if ($dir == 'manager') {
                    continue;
                }

                $socialiteProviders[] = $dir;
            }
        }

        return $socialiteProviders;
    }

    /**
     * Grab a list of the configured Providers
     *
     * @return array
     */
    public function getConfiguredProviders()
    {
        $installed = $this->getInstalledProviders();

        $configured = [];
        foreach ($installed as $provider) {
            // check to make sure both keys are set
            if (config(sprintf('services.%s.client_id', $provider), null) !== null &&
                    config(sprintf('services.%s.client_secret', $provider), null) !== null) {

                // assume its configured
                $configured[] = $provider;
            }
        }

        return $configured;
    }

}
