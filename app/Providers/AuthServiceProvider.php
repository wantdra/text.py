<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Word;
use App\Policies\UserPolicy;
use App\Policies\WordPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Word::class => WordPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function (User $user, string $ability) {
            return $user->isAdmin() ? true : null;
        });
    }
}
