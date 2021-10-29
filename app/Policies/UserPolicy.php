<?php declare(strict_types=1);

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
     * @param  User  $user
     * @param  User  $target
     * @return bool
     */
    public function affect(User $user, User $target)
    {
        if ($target->is_super_admin) {
            return false;
        }
        if ($user->is_super_admin) {
            return true;
        }
        if ('admin' === $user->role && 'admin' === $target->role) {
            return false;
        }
        
        return true;
    }
}
