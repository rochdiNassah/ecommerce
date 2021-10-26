<?php declare(strict_types=1);

namespace App\Services;

use App\Http\Responses\ServiceResponse;

class BaseService
{
    protected static $request;

    /** @param  \Illuminate\Http\Request|null  $request */
    public function __construct(\illuminate\Http\Request $request = null)
    {
        self::$request = $request;
    }

    /** @return void */
    public static function flashInputs(): void
    {
        self::$request->flashExcept('password');
    }

    /**
     * @param  string  $interface
     * @param  array  $response
     * @return void
     */
    protected static function createResponse($interface, $response): void
    {
        app()->singleton($interface, function () use ($response) {
            return new ServiceResponse($response);
        });
    }
}