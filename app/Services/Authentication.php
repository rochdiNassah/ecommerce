<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Http\Responses\ServiceResponse;
use App\Interfaces\Responses\{
    LoginResponse,
    LogoutResponse
};

class Authentication extends BaseService
{
    /**
     * Authentication attempt succeed.
     * 
     * @return void
     */
    public function succeed(): void
    {
        $response = [
            'status' => 'success',
            'message' => __('login.success'),
            'redirect_to' => route('dashboard')
        ];

        $this->createResponse(LoginResponse::class, $response);

        if ('pending' === Auth::user()->status) {
            $this->pending();
        }
    }

    /**
     * Authentication attempt failed.
     * 
     * @return void
     */
    public function failed(): void
    {
        $response = [
            'status' => 'error',
            'message' => __('login.failed'),
        ];

        $this->createResponse(LoginResponse::class, $response);
        $this->flashInputs();
    }

    /**
     * The user who logged in is still pending.
     * 
     * @return void
     */
    private function pending(): void
    {
        $response = [
            'status' => 'warning',
            'message' => __('login.pending')
        ];

        $this->createResponse(LoginResponse::class, $response);
        $this->flashInputs();
        $this->logout(true);
    }

    /**
     * Log the user out from the application.
     * 
     * @param  array|false  $response
     * @return void
     */
    public function logout($response = false): void
    {
        Auth::logout();

        $this->request->session()->regenerate();
        $this->request->session()->regenerateToken();

        if (!$response) {
            $response = [
                'status' => 'success',
                'message' => __('logout.success'),
                'redirect_to' => route('home')
            ];

            $this->createResponse(LogoutResponse::class, $response);
        }
    }

    /**
     * Flash inputs to the session.
     * 
     * @return void
     */
    private function flashInputs(): void
    {
        $this->request->flashExcept('password');
    }
}