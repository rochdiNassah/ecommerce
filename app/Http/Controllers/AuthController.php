<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage, Hash};
use App\Http\Requests\{LoginRequest, JoinRequest};
use App\Models\User;
use App\Services\{Authentication, RequestJoin, BaseService};
use App\Interfaces\Responses\{LoginResponse,LogoutResponse, RequestJoinResponse};

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
        $remember = $request->safe()->only('remmebr');

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
     * Log the user out from the application.
     * 
     * @param  \Illuminate\Http\Request
     * @return \App\Interfaces\Responses\LogoutResponse
     */
    public function logout(Request $request): LogoutResponse
    {
        Authentication::logout();

        return app(LogoutResponse::class);
    }

    /**
     * Store a join request.
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
}