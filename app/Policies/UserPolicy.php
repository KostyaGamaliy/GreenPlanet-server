<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function canDestroyUser(User $user) {
        return $user->role->name === 'Admin';
    }

    public function canViewCompanies(User $user) {
        return $user->role->name === "Admin";
    }

    public function canViewPlants(User $user) {
        return $user->role->name === "Admin";
    }

    public function canStoreCompany(User $user) {
        return $user->role->name === 'Admin';
    }

    public function canUpdateCompany(User $user) {
        return $user->role->name === 'Admin' || $user->role->name === 'Maneger';
    }

    public function canDeleteCompany(User $user) {
        return $user->role->name === 'Admin';
    }
}
