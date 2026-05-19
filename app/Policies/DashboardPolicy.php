<?php

namespace App\Policies;

use App\Models\User;

class DashboardPolicy
{
    /**
     * Create a new class instance.
     */
    public function viewAny(User $user)
    {
        return $user->role->name === 'admin';
    }
}
