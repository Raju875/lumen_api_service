<?php

namespace App\Services;

use Illuminate\Support\ServiceProvider;

class AuthRepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
       $this->app->bind(
           'App\Repositories\AuthInterface',
           'App\Repositories\AuthRepository'
       );
    }
}
