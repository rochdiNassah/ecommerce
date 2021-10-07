<?php

namespace App\Services;

use Illuminate\Contracts\Support\Responsable;
use App\Notifications\UserRejected;
use App\Services\UserService;

class DeleteUser extends UserService implements Responsable
{
    /**
     * Delete the given user.
     * 
     * @param  mixed  $user
     * @return void
     */
    public function delete($user)
    {
        $user->delete();

        'pending' === $user->status
            ? $user->notify(new UserRejected())
            : null;

        $this->response = [
            'status' => 'success',
            'message' => __('user.deleted')
        ];
    }
}