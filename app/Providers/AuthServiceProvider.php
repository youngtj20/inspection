<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-departments', function ($user) {
            return $this->hasRole($user, ['super-admin', 'admin']);
        });

        Gate::define('manage-users', function ($user) {
            return $this->hasRole($user, ['super-admin', 'admin']);
        });
    }

    private function hasRole($user, $roles)
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        $userRoles = \DB::table('sys_user_role')
            ->join('sys_role', 'sys_user_role.role_id', '=', 'sys_role.id')
            ->where('sys_user_role.user_id', $user->id)
            ->pluck('sys_role.name')
            ->toArray();

        return count(array_intersect($roles, $userRoles)) > 0;
    }
}
