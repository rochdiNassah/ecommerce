<?php declare(strict_types=1);

namespace App\Services;

use App\Http\Responses\ServiceResponse;

class BaseService
{
    protected static $request;

    /** @param  \Illuminate\Http\Request|null  $request */
    public function __construct($request = null)
    {
        self::$request = $request;
    }

    /** @return void */
    public static function flashInputs(): void
    {
        self::$request->flashExcept('password');
    }

    /**
     * @param  string  $abstract
     * @param  array  $response
     * @return void
     */
    protected static function createResponse($abstract, $response): void
    {
        app()->bind($abstract, function () use ($response) {
            return app(ServiceResponse::class, ['response' => $response]);
        }, 1);
    }

    /** @return void */
    public static function publish($object): void
    {
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');

        $socket->connect('tcp://localhost:5555');
        $socket->send(json_encode($object));
    }
}
