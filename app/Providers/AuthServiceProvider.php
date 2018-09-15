<?php

namespace App\Providers;

use App\Network;
use App\Nursery;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model'         => 'App\Policies\ModelPolicy',
        User::class         => 'App\Policies\UserPolicy',
        Nursery::class      => 'App\Policies\NurseryPolicy',
        Network::class      => 'App\Policies\NetworkPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
