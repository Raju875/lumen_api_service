<?php

namespace App\Services;

use Illuminate\Support\ServiceProvider;

class TokenRepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'App\Repositories\TokenInterface',
            'App\Repositories\TokenRepository'
        );
    }
}
