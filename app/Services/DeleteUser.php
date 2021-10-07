<?php

namespace App\Services;

use App\Notifications\UserRejected;

class DeleteUser extends Service
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