<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\Responses\UpdateMemberRoleResponse;

class UpdateMemberRole extends BaseService
{
    /**
     * Update the given member's role.
     * 
     * @param  \App\Models\Member  $member
     * @param  string  $role
     * @param  string  $action
     * @return void
     */
    public static function update($member, string $role, string $action): void
    {
        $member->role = $role;

        $member->save();

        $response = [
            'status' => 'success',
            'message' => __("member.{$action}d"),
            'reason' => ucfirst("{$action}d"),
            'redirect_to' => route('members')
        ];

        self::createResponse(UpdateMemberRoleResponse::class, $response);
    }

    /**
     * @param  string  $message
     * @return void
     */
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
     * @param  string|null  $current_role
     * @param  string  $new_role
     * @return false|string
     */
    public static function getAction(string|null $current_role, string $new_role): false|string
    {
        $roles = ['delivery_driver', 'dispatcher', 'admin'];
        
        $current_role = array_search($current_role ?? $roles[0], $roles);
        $new_role = array_search($new_role, $roles);
        
        return $current_role < $new_role
            ? 'upgrade'
            : 'downgrade';
    }
}
