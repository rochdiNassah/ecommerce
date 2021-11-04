<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Interfaces\Responses\{LoginResponse, LogoutResponse};

class Authentication extends BaseService
{
    /** @return void */
    public static function loginFailed(): void
    {
        $response = [
            'status' => 'error',
            'message' => __('login.failed'),
        ];

        self::createResponse(LoginResponse::class, $response);
        self::flashInputs();
    }

    /**
     * Authentication attempt succeed.
     * 
     * @return void
     */
    public static function loginSucceed(): void
    {
        $response = [
            'status' => 'success',
            'message' => __('login.success'),
            'redirect_to' => route('dashboard')
        ];

        self::createResponse(LoginResponse::class, $response);
    }

    /** @return void */
    public static function memberIsPending(): void
    {
        $response = [
            'status' => 'warning',
            'message' => __('login.pending')
        ];
        
        self::createResponse(LoginResponse::class, $response);
        self::flashInputs();
        self::logout($response);
    }

    /**
     * Log the member out from the application.
     * 
     * @param  array|false  $response
     * @return void
     */
    public static function logout($response = false): void
    {
        Auth::logout();

        self::$request->session()->regenerate();
        self::$request->session()->regenerateToken();

        if (!$response) {
            $response = [
                'status' => 'success',
                'message' => __('logout.success'),
                'redirect_to' => route('home')
            ];

            self::createResponse(LogoutResponse::class, $response);
        }
    }
}