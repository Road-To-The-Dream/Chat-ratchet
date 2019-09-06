<?php

namespace App\Providers;

use App\Http\Controllers\AuthController;
use App\Services\AuthService;
use App\Services\GitHubAuthService;
use App\Services\GoogleAuthService;
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

        $this->app->singleton(AuthService::class, function ($app) use ($auth){
            if($auth === 'google' || $_SERVER['PATH_INFO'] === '/callback') {
                return new GoogleAuthService();
            }

            return new GitHubAuthService();
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
        return [AuthService::class];
    }
}
