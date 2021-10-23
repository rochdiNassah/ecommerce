<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\{LoginRequest, JoinRequest};
use App\Models\User;
use App\Services\{Authentication, RequestJoin};
use App\Interfaces\Responses\{
    LoginResponse,
    LogoutResponse
};

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     * 
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \App\Interfaces\Responses\LoginResponse
     */
    public function login(LoginRequest $request): LoginResponse
    {
        $service = app(
            Authentication::class,
            ['request' => $request]
        );
        $credentials = $request->safe()->only('email', 'password');
        $remember =  $request->safe()->only('remember');

        Auth::attempt($credentials, $remember)
            ? $service->succeed()
            : $service->failed();

        return app(LoginResponse::class);
    }

    /**
     * Log the user out from the application.
     * 
     * @param  \Illuminate\Http\Request
     * @return \App\Interfaces\Responses\LogoutResponse
     */
    public function logout(Request $request): LogoutResponse
    {
        $service = app(
            Authentication::class,
            ['request' => $request]
        );

        $service->logout();

        return app(LogoutResponse::class);
    }

    /**
     * Store a join request.
     * 
     * @param  \App\Http\Requests\JoinRequest
     * @return \App\Services\RequestJoin
     */
    public function join(JoinRequest $request): RequestJoin
    {
        $service = app(
            RequestJoin::class,
            ['request' => $request]
        );

        $service->store();
        
        return $service;
    }
}