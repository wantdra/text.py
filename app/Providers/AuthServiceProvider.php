<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Word;
use App\Policies\UserPolicy;
use App\Policies\WordPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Word::class => WordPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->uncompromised();
        });

        Gate::before(function (User $user, string $ability) {
            return $user->isAdmin() ? true : null;
        });
    }
}
