<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the given user can be deleted.
     * 
     * @param  User  $user
     * @param  User  $target
     * @return bool
     */
    public function delete(User $user, User $target)
    {
        if ($target->is_super_admin) {
            return false;
        }

        return 'admin' === $user->role ? true : false;
    }

    public function updateRole(User $user, User $target)
    {
        return $this->delete($user, $target);
    }
}
