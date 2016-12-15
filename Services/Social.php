<?php

namespace Cms\Modules\Social\Services;

use Cms\Modules\Auth\Repositories\User\RepositoryInterface as UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Cms\Modules\Social\Models\UserProvider;
use Illuminate\Contracts\Auth\Guard;

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
     * @param Socialite      $socialite
     * @param Guard          $auth
     * @param UserRepository $user
     */
    public function __construct(Socialite $socialite, Guard $auth, UserRepository $user)
    {
        $this->socialite = $socialite;
        $this->user = $user;
        $this->auth = $auth;
    }

    /**
     * Run the process for logging in via Socialite.
     */
    public function loginThirdParty($request, $provider)
    {
        $provider = strtolower(trim($provider));

        // if we dont have this provider, error out
        if (!in_array($provider, $this->getConfiguredProviders())) {
            return redirect(route('pxcms.pages.home'))->withError('This provider is not supported.');
        }

        // travelling somewhere...
        if (!$request) {
            return $this->getAuthorizationFirst($provider);
        }
        $socialiteUser = $this->getSocialUser($provider);

        // if user is a guest try and log em in
        if ($this->auth->guest()) {
            // check if the provider exists
            if (($user = $this->getByProvider($provider, $socialiteUser)) !== null) {
                return $this->loginUser($user);
            }

            // grab the user
            $user = $this->getOrCreateUser($provider, $socialiteUser);

            return $this->loginUser($user);

        // otherwise link their social to their real account
        } else {
            $user = $this->auth->getUser();
            if (!$user->hasProvider($provider)) {
                $this->createSocialLink($user, $socialiteUser, $provider);
            }

            return redirect()
                ->back()
                ->withInfo('Your '.$provider.' account has been linked. You can use this to login from now on.');
        }
    }

    public function removeProvider($user_id, $provider)
    {

        // grab the user object
        $authModel = config('cms.auth.config.user_model');

        $user = with(new $authModel())
                ->with('providers')
                ->find($user_id);

        if ($user === null) {
            return redirect()->back()->withError('There seems to be a problem finding the user account, try again later.');
        }

        // sanity check we actually have some providers
        if ($user->providers->count() == 0) {
            return redirect()->back()->withError('This user account doesn\'t seem to have any providers attached.');
        }

        // make sure they have the provider we are looking to remove
        $rmProvider = $user->providers->filter(function ($row) use ($provider) {
            return $row->provider === $provider;
        });
        if ($rmProvider === null) {
            return redirect()->back()->withError('Could not find the requested provider to remove.');
        }

        // and remove it!
        return $rmProvider->first()->delete();
    }

    /**
     * Log the user in.
     */
    private function loginUser($user)
    {
        $model = config('cms.auth.config.user_model');
        if (!($user instanceof $model)) {
            throw new \Exception('No valid user returned');
        }

        // log the user in & fire the logged in event
        $this->auth->login($user, true);
        event(new \Cms\Modules\Auth\Events\UserHasLoggedIn($user->id));

        return redirect()->intended(route(config('cms.auth.paths.redirect_login', 'pxcms.pages.home')));
    }

    /**
     * Check to see if this user provider already exists, if so return the user.
     */
    private function getByProvider($provider, $socialiteUser)
    {
        try {
            $userProvider = with(new UserProvider())->where('email', $socialiteUser->email)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return;
        }

        return $this->user->getById($userProvider->user_id);
    }

    /**
     * Check if the user is already in the db, if not create it.
     */
    private function getOrCreateUser($provider, $socialiteUser)
    {
        try {
            $user = $this->user->where('email', $socialiteUser->email)->first();
        } catch (ModelNotFoundException $e) {
            $user = $this->createUserWithSocialiteDetails($socialiteUser);
        }

        if (!$user->hasProvider($provider)) {
            $this->createSocialLink($user, $socialiteUser, $provider);
        }

        return $user;
    }

    /**
     * Create a user using the socialite details.
     */
    private function createUserWithSocialiteDetails($socialiteUser)
    {
        $details = [
            'username' => $socialiteUser->nickname,
            'name' => $socialiteUser->name,
            'email' => $socialiteUser->email,
            'avatar' => $socialiteUser->avatar,
        ];

        if (empty($socialiteUser->nickname)) {
            $details['use_nick'] = 1;
        }

        return $this->user->createWithRoles($details, config('cms.auth.config.roles.user_group'), true);
    }

    /**
     * Create a social user.
     */
    private function createSocialLink($user, $socialiteUser, $provider)
    {
        return with(new UserProvider())->fill([
            'username' => $socialiteUser->nickname,
            'name' => $socialiteUser->name,
            'email' => $socialiteUser->email,
            'avatar' => $socialiteUser->avatar,
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $socialiteUser->id,
        ])->save();
    }

    /**
     * If we need authorization, redirect!
     */
    private function getAuthorizationFirst($provider)
    {
        return $this->socialite->driver($provider)->redirect();
    }

    /**
     * Get the user details from socialite.
     */
    private function getSocialUser($provider)
    {
        return $this->socialite->driver($provider)->user();
    }

    /**
     * Grab the list of providers.
     *
     * @return array
     */
    public function getProviders()
    {
        return config('cms.social.providers', []);
    }

    /**
     * Grab a list of the installed Providers.
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

                $socialiteProviders[] = strtolower($dir);
            }
        }

        return $socialiteProviders;
    }

    /**
     * Grab a list of the configured Providers.
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
                $configured[] = strtolower($provider);
            }
        }

        return $configured;
    }
}
