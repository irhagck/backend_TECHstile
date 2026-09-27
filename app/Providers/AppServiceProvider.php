<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        PasswordRule::defaults(function () {
            $rule = PasswordRule::min(8)->letters()->mixedCase()->numbers()->symbols();
            return $this->app->isProduction() ? $rule->uncompromised() : $rule;
        });
    }
}