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
    public static function approve($member): void
    {
        $member->status = 'active';

        $member->save();
    }

    /**
     * @param  \App\Models\User  $member
     * @return void
     */
    public static function notify($member): void
    {
        $member->notify(app(UserApproved::class));
    }

    /** @return void */
    public static function succeed(): void
    {
        $response = [
            'status' => 'success',
            'message' => __('member.approved')
        ];

        self::createResponse(ApproveMemberResponse::class, $response);
    }

    /** @return void */
    public static function failed(): void
    {
        $response = [
            'status' => 'error',
            'message' => __('global.failed')
        ];

        self::createResponse(ApproveMemberResponse::class, $response);
    }

    /** @return void */
    public static function already(): void
    {
        $response = [
            'status' => 'warning',
            'message' => __('member.already.approved'),
            'reason' => 'Already'
        ];

        self::createResponse(ApproveMemberResponse::class, $response);
    }
}