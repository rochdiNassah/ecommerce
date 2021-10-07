<?php

namespace App\Services;

use Illuminate\Contracts\Support\Responsable;
use App\Services\UserService;

class EditUserRole extends UserService implements Responsable
{
    protected $redirectTo = 'users';

    /**
     * Update the given user's role.
     * 
     * @param  mixed  $user
     * @return void
     */
    public function update($user, $role, $action)
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
    public function action($user, string $role)
    {
        if ($user->role === $role) return false;

        $roles = [
            'admin' => 999,
            'dispatcher' => 666,
            'delivery_driver' => 333
        ];

        return $roles[$role] > $roles[$user->role]
            ? 'upgrade'
            : 'downgrade';
    }
}