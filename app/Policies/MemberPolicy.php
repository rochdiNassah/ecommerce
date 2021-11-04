<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\Member;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberPolicy
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
     * @param  Member  $member
     * @param  Member  $target
     * @return bool
     */
    public function affect(Member $member, Member $target)
    {
        if ($target->is_super_admin) {
            return false;
        }
        if ($member->is_super_admin) {
            return true;
        }
        if ('admin' === $member->role && 'admin' === $target->role) {
            return false;
        }
        
        return true;
    }
}
