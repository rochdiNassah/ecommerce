<?php declare(strict_types=1);

namespace App\Services;

use App\Notifications\MemberRejected;
use App\Jobs\RejectMember;
use App\Interfaces\Responses\DeleteMemberResponse;

class DeleteMember extends BaseService
{
    /**
     * Delete the given member.
     * 
     * @param  \App\Models\Member  $member
     * @return void
     */
    public static function delete($member): void
    {
        $member->forceDelete();

        $response = [
            'status' => 'success',
            'message' => __('member.deleted')
        ];

        self::createResponse(DeleteMemberResponse::class, $response);
    }

    /**
     * Notify the given member, then delete them.
     * 
     * @param  \App\Models\Member  $member
     * @return void
     */
    public static function rejectMember($member): void
    {
        $member->delete();
        $member->notify(app(MemberRejected::class));

        RejectMember::dispatch($member);

        $response = [
            'status' => 'success',
            'message' => __('member.rejected')
        ];

        self::createResponse(DeleteMemberResponse::class, $response);
    }
}