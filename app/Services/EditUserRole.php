<?php declare(strict_types=1);

namespace App\Services;

class EditUserRole extends Service
{
    public $redirectTo = 'users';

    /**
     * Update the given user's role.
     * 
     * @param  mixed  $user
     * @return void
     */
    public function update($user, $role, $action): void
    {
        $user->role = $role;

        $user->save();

        $this->response = [
            'status' => 'success',
            'message' => __("user.{$action}d"),
            'reason' => ucfirst("{$action}d")
        ];
    }

    /**
     * Determine if the given action is upgrade or downgrade.
     * 
     * @param  \App\Models\User  $data
     * @param  string  $role
     * @return false|string
     */
    public function action($user, string $role): false|string
    {
        $roles = ['admin' => 999, 'dispatcher' => 666, 'delivery_driver' => 333];
        
        return $user->role === $role
            ? false
            : ($roles[$role] > ($roles[$user->role] ?? 333)
                ? 'upgrade'
                : 'downgrade');
    }
}