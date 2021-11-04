<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Password;
use App\Interfaces\Responses\ForgotPasswordResponse;

class ForgotPassword extends BaseService
{
    /**
     * Send password reset link to the given email address.
     * 
     * @param  array  $credentials
     * @return bool
     */
    public static function sendLink(array $credentials): bool
    {
        return Password::RESET_LINK_SENT === Password::sendResetLink($credentials)
            ? true
            : false;
    }

    /** @return void */
    public static function succeed(): void
    {
        $response = [
            'status' => 'success',
            'message' => __('passwords.sent'),
            'redirect_to' => route('login')
        ];

        self::createResponse(ForgotPasswordResponse::class, $response);
    }

    /** @return void */
    public static function failed(): void
    {
        $response = [
            'status' => 'error',
            'message' => __('passwords.user')
        ];

        self::createResponse(ForgotPasswordResponse::class, $response);
    }
}