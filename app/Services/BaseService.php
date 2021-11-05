<?php declare(strict_types=1);

namespace App\Services;

use App\Http\Responses\ServiceResponse;
use ZmqContext;
use ZmqSocket;
use Zmq;

class BaseService
{
    /** @var \Illuminate\Http\Request */
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
        app()->singleton($abstract, function () use ($response) {
            return app(ServiceResponse::class, ['response' => $response]);
        });
    }

    /**
     * Create a queue object and send the event to subscribers.
     * 
     * @param  \App\Models\Model  $event
     * @return void
     */
    public static function publish($event): void
    {
        if ('testing' === config('app.env')) {
            return;
        }

        $context = new ZMQContext();
        $socket = $context->getSocket(ZMQ::SOCKET_PUSH);
        $sock_pull_host = config('ratchet.sockpull.host');
        $sock_pull_port = config('ratchet.sockpull.port');

        $socket->connect(sprintf('tcp://%s:%s', $sock_pull_host, $sock_pull_port));
        $socket->send(json_encode($event));
    }
}
