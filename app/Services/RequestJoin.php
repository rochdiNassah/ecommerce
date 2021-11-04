<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Notifications\JoinRequested;
use App\Models\Member;
use App\Interfaces\Responses\RequestJoinResponse;
use Illuminate\Support\Facades\Storage;

class RequestJoin extends BaseService
{
    /**
     * @param  array  $validated
     * @return false|\App\Models\Member
     */
    public static function store(array $validated): false|Member
    {
        return Member::create($validated);
    }

    /**
     * @param  \App\Models\Member  $member
     */
    public static function notify(Member $member)
    {
        $member->notify(app(JoinRequested::class));
    }

    /** @return void */
    public static function succeed(): void
    {
        $response = [
            'status' => 'success',
            'message' => __('join.success'),
            'redirect_to' => route('login')
        ];

        self::createResponse(RequestJoinResponse::class, $response);
    }

    /** @return void */
    public static function failed(): void
    {
        $response = [
            'status' => 'error',
            'message' => __('global.failed')
        ];

        self::createResponse(RequestJoinResponse::class, $response);
        self::flashInputs();
    }
}