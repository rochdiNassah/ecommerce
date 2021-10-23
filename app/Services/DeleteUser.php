<?php declare(strict_types=1);

namespace App\Services;

use App\Notifications\UserRejected;
use App\Jobs\RejectUser;

class DeleteUser extends Service
{
    /**
     * Delete the given user.
     * 
     * @param  \App\Models\User  $user
     * @return void
     */
    public function delete($user)
    {
        $user->forceDelete();

        $this->response = [
            'status' => 'success',
            'message' => __('user.deleted')
        ];
    }

    /**
     * Notify the given user, then delete them.
     * 
     * @param  \App\Models\User  $user
     * @return void
     */
    public function rejectUser($user): void
    {
        $user->delete();
        $user->notify(new UserRejected());

        RejectUser::dispatch($user);

        $this->response = [
            'status' => 'success',
            'message' => __('user.rejected')
        ];
    }
}