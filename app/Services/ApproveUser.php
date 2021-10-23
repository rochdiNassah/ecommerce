<?php declare(strict_types=1);

namespace App\Services;

use App\Notifications\UserApproved;

class ApproveUser extends Service
{
    /**
     * Approve the given user.
     * 
     * @param  \App\Models\User  $user
     * @return void
     */
    public function approve($user): void
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