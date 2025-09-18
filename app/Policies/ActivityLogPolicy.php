<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ActivityLog;

class ActivityLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('activity_logs.view');
    }

    public function view(User $user, ActivityLog $activityLog): bool
    {
        return $user->hasPermission('activity_logs.view');
    }

    public function delete(User $user, ActivityLog $activityLog): bool
    {
        return $user->hasPermission('activity_logs.delete');
    }
}