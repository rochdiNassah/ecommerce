<?php declare(strict_types=1);

namespace App\Services;

use App\Http\Responses\ServiceResponse;
use ZmqContext;
use ZmqSocket;
use Zmq;

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

    /**
     * Create a queue object and send the event to subscribers.
     * 
     * @param  \App\Models\Model  $event
     * @return void
     */
    public static function publish($event): void
    {
        $queue = new ZmqSocket(
            app(ZmqContext::class),
            Zmq::SOCKET_REQ,
            'socket-one'
        );

        $queue->connect('tcp://0.0.0.0:1111');
        $queue->send(json_encode($event));
    }
}
