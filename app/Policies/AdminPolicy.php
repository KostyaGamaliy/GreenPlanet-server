<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class AdminPolicy
{
    /**
     * Create a new policy instance.
     */
    public function canDestroyUser(User $user) {
        return $user->role->name === 'Admin';
    }

    public function canStoreCompany(User $user) {
        return $user->role->name === 'Admin';
    }
}
