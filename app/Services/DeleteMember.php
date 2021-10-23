<?php declare(strict_types=1);

namespace App\Services;

use App\Notifications\UserRejected;
use App\Jobs\RejectUser;
use App\Interfaces\Responses\DeleteMemberResponse;

class DeleteMember extends BaseService
{
    /**
     * Delete the given member.
     * 
     * @param  \App\Models\User  $member
     * @return void
     */
    public function delete($member)
    {
        $member->forceDelete();

        $response = [
            'status' => 'success',
            'message' => __('member.deleted')
        ];

        $this->createResponse(DeleteMemberResponse::class, $response);
    }

    /**
     * Notify the given user, then delete them.
     * 
     * @param  \App\Models\User  $member
     * @return void
     */
    public function rejectMember($member): void
    {
        $member->delete();
        $member->notify(new UserRejected());

        RejectUser::dispatch($member);

        $response = [
            'status' => 'success',
            'message' => __('member.rejected')
        ];

        $this->createResponse(DeleteMemberResponse::class, $response);
    }
}