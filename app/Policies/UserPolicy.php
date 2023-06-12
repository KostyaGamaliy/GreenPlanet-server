<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function canViewCompanies(User $user) {
        return $user->role->name === "Admin";
    }

    public function canViewPlants(User $user) {
        return $user->role->name === 'Admin' || $user->role->name === 'Maneger' || $user->role->name === 'Employee';
    }

    public function canViewRoles(User $user) {
        return $user->role->name === 'Admin';
    }

    public function canViewCompany(User $user) {
        return $user->role->name === 'Admin' || $user->role->name === 'Maneger' || $user->role->name === 'Employee';
    }

    public function canViewApplications(User $user) {
        return $user->role->name === 'Admin';
    }

    public function canGetUsersInCompany(User $user) {
        return $user->role->name === 'Admin';
    }

    public function canGetUsersExceptCompany(User $user) {
        return $user->role->name === 'Admin' || $user->role->name === 'Maneger';
    }

    public function canStoreCompany(User $user) {
        return $user->role->name === 'Admin' || $user->role->name === 'Maneger';
    }

    public function canStorePlant(User $user) {
        return $user->role->name === 'Admin' || $user->role->name === 'Maneger' || $user->role->name === 'Employee';
    }

    public function canStoreRole(User $user) {
        return $user->role->name === 'Admin';
    }

    public function canStoreIotDates(User $user) {
        return $user->role->name === 'Admin';
    }

    public function canStoreUserToCompany(User $user) {
        return $user->role->name === 'Admin';
    }

    public function canUpdateCompany(User $user) {
        return $user->role->name === 'Admin' || $user->role->name === 'Maneger';
    }

    public function canUpdateRole(User $user) {
        return $user->role->name === 'Admin';
    }

    public function canDeleteCompany(User $user) {
        return $user->role->name === 'Admin';
    }

    public function canDeletePlant(User $user) {
        return $user->role->name === 'Admin' || $user->role->name === 'Maneger';
    }

    public function canDestroyUser(User $user) {
        return $user->role->name === 'Admin';
    }

    public function canDestroyRole(User $user) {
        return $user->role->name === 'Admin';
    }

    public function canRemoveFromCompany(User $user) {
        return $user->role->name === 'Admin' || $user->role->name === 'Maneger';
    }
}
