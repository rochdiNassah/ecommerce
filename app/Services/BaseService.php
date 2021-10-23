<?php declare(strict_types=1);

namespace App\Services;

use App\Http\Responses\ServiceResponse;

class BaseService
{
    protected $request;

    public function __construct($request = null)
    {
        $this->request = $request;
    }

    /**
     * @param  string  $interface
     * @param  array  $response
     * @return void
     */
    protected function createResponse($interface, $response): void
    {
        app()->singleton($interface, function () use ($response) {
            return new ServiceResponse($response);
        });
    }
}