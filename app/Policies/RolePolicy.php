<?php

namespace App\Policies;

use App\Models\User;
use Silber\Bouncer\Database\Role;

class RolePolicy
{
    /**
     * Create a new policy instance.
     */
    public function updateRolesPermissions(User $user, Role $role)
    {
        if ($role->id == 1) {
            return false;
        }
        return true;
    }
}
