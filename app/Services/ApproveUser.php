<?php

namespace App\Services;

use Illuminate\Contracts\Support\Responsable;
use App\Notifications\UserApproved;
use App\Services\UserService;

class ApproveUser extends UserService implements Responsable
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