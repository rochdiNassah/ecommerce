<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\Responses\UpdateMemberRoleResponse;

class EditMemberRole extends BaseService
{
    public $redirectTo = 'users';

    /**
     * Update the given member's role.
     * 
     * @param  mixed  $member
     * @return void
     */
    public static function update($member, $role, $action): void
    {
        $member->role = $role;

        $member->save();

        $response = [
            'status' => 'success',
            'message' => __("member.{$action}d"),
            'reason' => ucfirst("{$action}d"),
            'redirect_to' => route('users')
        ];

        self::createResponse(UpdateMemberRoleResponse::class, $response);
    }

    /** @return void */
    public static function already($message): void
    {
        $response = [
            'status' => 'warning',
            'message' => __($message)
        ];

        self::createResponse(UpdateMemberRoleResponse::class, $response);
    }

    /**
     * Determine if the given action is upgrade or downgrade.
     * 
     * @param  \App\Models\User  $member
     * @param  string  $role
     * @return false|string
     */
    public static function getAction($member, string $role): false|string
    {
        $roles = ['admin' => 999, 'dispatcher' => 666, 'delivery_driver' => 333];
        
        return $member->role === $role
            ? false
            : ($roles[$role] > ($roles[$member->role] ?? 333)
                ? 'upgrade'
                : 'downgrade');
    }
}