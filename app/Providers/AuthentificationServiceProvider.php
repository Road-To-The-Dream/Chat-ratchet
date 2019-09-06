<?php

namespace App\Providers;

use App\Http\Controllers\AuthController;
use App\Services\Auth;
use App\Services\GitHubAuth;
use App\Services\GoogleAuth;
use Illuminate\Support\ServiceProvider;

class AuthentificationServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        $auth = $this->app->make('request')->get('auth');

        $this->app->singleton(Auth::class, function ($app) use ($auth){
            if($auth === 'google') {
                return new GoogleAuth();
            }

            return new GitHubAuth();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function provides()
    {
        return [Auth::class];
    }
}
