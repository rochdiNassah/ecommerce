<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\{Password, Hash};
use Illuminate\Auth\Events\PasswordReset;
use App\Interfaces\Responses\ResetPasswordResponse;

class ResetPassword extends BaseService
{
    /**
     * @param  array  $validated
     * @param  string  $token
     * @return bool
     */
    public static function reset(array $validated, string $password): bool
    {
        $callback = function ($member) use ($password) {
            $member->forceFill(['password' => $password])
                ->setRememberToken(bin2hex(random_bytes(30)));
            $member->save();

            event(new PasswordReset($member));
        };

        return Password::PASSWORD_RESET === Password::reset($validated, $callback)
            ? true
            : false;
    }

    /** @return void */
    public static function succeed(): void
    {
        $response = [
            'status' => 'success',
            'message' => __('passwords.reset'),
            'redirect_to' => route('login')
        ];

        self::createResponse(ResetPasswordResponse::class, $response);
    }

    /** @return void */
    public static function failed(): void
    {
        $response = [
            'status' => 'error',
            'message' => __('passwords.token')
        ];

        self::createResponse(ResetPasswordResponse::class, $response);
    }
}