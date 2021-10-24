<?php declare(strict_types=1);

namespace App\Services;

use App\Notifications\UserApproved;
use App\Interfaces\Responses\ApproveMemberResponse;

class ApproveMember extends BaseService
{
    /**
     * Approve the given member.
     * 
     * @param  \App\Models\User  $member
     * @return void
     */
    public function approve($member): void
    {
        $member->status = 'active';

        $member->save();
        $member->notify((new UserApproved()));
        
        $response = [
            'status' => 'success',
            'message' => __('member.approved')
        ];

        $this->createResponse(ApproveMemberResponse::class, $response);
    }

    /** @return void */
    public function already(): void
    {
        $response = [
            'status' => 'warning',
            'message' => __('member.already.approved'),
            'reason' => 'Already'
        ];

        $this->createResponse(ApproveMemberResponse::class, $response);
    }
}