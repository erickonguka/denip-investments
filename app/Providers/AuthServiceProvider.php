<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Project;
use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use App\Policies\ProjectPolicy;
use App\Policies\UserPolicy;
use App\Policies\RolePolicy;
use App\Policies\ActivityLogPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Project::class => ProjectPolicy::class,
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        ActivityLog::class => ActivityLogPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}