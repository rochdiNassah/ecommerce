<?php

namespace App\Services;

use Illuminate\Contracts\Support\Responsable;
use App\Notifications\{UserApproved, UserRejected};

class ApproveUser implements Responsable
{
    private $response = [];

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

    /**
     * The given user is alread active.
     * 
     * @return void
     */
    public function already()
    {
        $this->response = [
            'status' => 'warning',
            'message' => __('user.active'),
            'reason' => 'Already'
        ];
    }

    /**
     * The given user is not found.
     * 
     * @return void
     */
    public function notFound()
    {
        $this->response = [
            'status' => 'error',
            'message' => __('user.missing'),
            'reason' => 'Not Found'
        ];
    }

    /**
     * The given user is not found.
     */

    public function toResponse($request)
    {
        return back()->with($this->response);
    }
}