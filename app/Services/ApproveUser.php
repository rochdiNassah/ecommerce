<?php

namespace App\Services;

use App\Notifications\UserApproved;

class ApproveUser extends Service
{
    /**
     * Approve the given user.
     * 
     * @param  mixed  $user
     * @return void
     */
    public function approve($user)
    {
        $user->status = 'active';
        $user->save();
        $user->notify((new UserApproved()));

        $this->response = [
            'status' => 'success',
            'message' => __('user.approved')
        ];
    }
}