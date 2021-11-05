<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage, Hash};
use App\Http\Requests\{LoginRequest, JoinRequest, ResetPasswordRequest};
use App\Models\Member;
use App\Services\{Authentication, RequestJoin, BaseService, ForgotPassword, ResetPassword};
use App\Interfaces\Responses\{LoginResponse, LogoutResponse, RequestJoinResponse};
use App\Interfaces\Responses\{ForgotPasswordResponse, ResetPasswordResponse};

class AuthController extends Controller
{
    /** @param  \Illuminate\Http\Request  $request */
    public function __construct(Request $request)
    {
        app(BaseService::class, ['request' => $request]);
    }

    /**
     * Handle an authentication attempt.
     * 
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \App\Interfaces\Responses\LoginResponse
     */
    public function login(LoginRequest $request): LoginResponse
    {
        $credentials = $request->safe()->only([
            'email', 'password'
        ]);
        $remember = $request->safe()->only('remember');

        if (!Auth::attempt($credentials, $remember)) {
            Authentication::loginFailed();
        } else {
            'pending' === Auth::user()->status
                ? Authentication::memberIsPending()
                : Authentication::loginSucceed();
        }    

        return app(LoginResponse::class);
    }

    /**
     * Log the member out from the application.
     * 
     * @return \App\Interfaces\Responses\LogoutResponse
     */
    public function logout(): LogoutResponse
    {
        Authentication::logout();

        return app(LogoutResponse::class);
    }

    /**
     * Handle a join request form submission.
     * 
     * @param  \App\Http\Requests\JoinRequest
     * @return \App\Interfaces\Responses\RequestJoinResponse
     */
    public function join(JoinRequest $request): RequestJoinResponse
    {
        $validated = $request->safe()->only([
            'fullname', 'email', 'phone_number', 'password', 'role', 'avatar'
        ]);
        $validated['password'] = Hash::make($validated['password']);

        if ($request->file('avatar')) {
            $validated['avatar_path'] = Storage::put('images/avatars', $validated['avatar']);
        }
        if ($user = RequestJoin::store($validated)) {
            RequestJoin::notify($user);
            RequestJoin::succeed();
        } else {
            RequestJoin::failed();
        }
        
        return app(RequestJoinResponse::class);
    }

    /**
     * Send password reset link.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Interfaces\Responses\ForgotPasswordResponse
     */
    public function forgotPassword(Request $request): ForgotPasswordResponse
    {
        $request->validate(['email' => 'email']);

        if (ForgotPassword::sendLink($request->only('email'))) {
            ForgotPassword::succeed();
        } else {
            ForgotPassword::failed();
        }

        return app(ForgotPasswordResponse::class);
    }

    /**
     * Handle reset password form submission.
     * 
     * @param  \App\Http\Requests\ResetPasswordRequest
     * @return \App\Interfaces\Responses\ResetPasswordResponse
     */
    public function resetPassword(ResetPasswordRequest $request): ResetPasswordResponse
    {
        $validated = $request->only([
            'token', 'email', 'password', 'password_confirmation'
        ]);
        $password = Hash::make($validated['password']);

        if (ResetPassword::reset($validated, $password)) {
            ResetPassword::succeed();
        } else {
            ResetPassword::failed();
        }

        return app(ResetPasswordResponse::class);
    }
}
