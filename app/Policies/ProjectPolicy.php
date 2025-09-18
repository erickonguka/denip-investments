<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function view(User $user, Project $project)
    {
        return $user->client && $user->client->id === $project->client_id;
    }

    public function update(User $user, Project $project)
    {
        return $user->client && $user->client->id === $project->client_id && in_array($project->status, ['planning']);
    }

    public function delete(User $user, Project $project)
    {
        return $user->client && $user->client->id === $project->client_id && $project->status === 'planning';
    }
}