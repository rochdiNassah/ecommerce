<?php declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\ZMQ\Context;
use React\Socket\Server;
use Ratchet\Wamp\WampServer;
use App\Services\Ratchet;
use Zmq;

class StartRatchetCommand extends Command
{
    /** @var \React\EventLoop\Factory */
    private $loop;

    /** @var \App\Services\Ratchet */
    private $app;

    /** @var \React\ZMQ\Context */
    private $context;

    /** @var \React\Socket\Server */
    private $ws_server;

    /** @var \Ratchet\Wamp\WampServer */
    private $wamp_component;

    /** @var \Ratchet\WebSocket\WsServer  */
    private $ws_component;

    /** @var \Ratchet\Http\HttpServer */
    private $http_server;

    /** @var \Ratchet\Server\IoServer */
    private $io_server;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ratchet:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start Ratcher server to listen for WebSocket connections.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!extension_loaded('zmq')) {
            $this->error('Zmq PHP extension is missing from your server.');

            return Command::SUCCESS;
        } elseif (!class_exists(Context::class)) {
            $consent = $this->confirm('react/zmq package is missing! Do you want to install it?', 1);
        
            if (!$consent) {
                return Command::SUCCESS;
            }

            @unlink('composer.lock');
            exec('composer require "react/zmq:0.2.*|0.3.*"');
            exec('composer update');

            $this->info('All set! Please run `php artisan ratchet:start` again.');

            return Command::SUCCESS;
        }

        $this->loop = app(Factory::class)::create();
        $this->app =  app(Ratchet::class);
        $this->context = $this->getContext();
        $this->wamp_component = $this->wampComponent();
        $this->ws_component = $this->wsComponent();
        $this->ws_server = $this->wsServer();
        $this->http_server = $this->httpServer();
        $this->io_server = $this->ioServer();
        $this->pull = $this->context->getSocket(Zmq::SOCKET_PULL);

        $sock_pull_host = config('ratchet.sockpull.host');
        $sock_pull_port = config('ratchet.sockpull.port');
        
        $this->pull->bind(sprintf('tcp://%s:%s', $sock_pull_host, $sock_pull_port));
        $this->pull->on('message', [$this->app, 'onOrderEntry']);
        $this->loop->run();

        return Command::SUCCESS;
    }

    /**
     * Create context.
     * 
     * @return \React\ZMQ\Context
     */
    private function getContext()
    {
        return app(Context::class, ['loop' => $this->loop]);
    }

    /**
     * Create WAMP component.
     * 
     * @return \Ratchet\Wamp\WampServer
     */
    private function wampComponent()
    {
        return app(WampServer::class, ['app' => $this->app]);
    }

    /**
     * Create WebSocket component.
     * 
     * @return \Ratchet\WebSocket\WsServer
     */
    private function wsComponent()
    {
        return app(WsServer::class, ['component' => $this->wamp_component]);
    }

    /** 
     * Create WebSocket server.
     * 
     * @return \React\Socket\Server
     */
    private function wsServer(): Server
    {
        $ws_host = config('ratchet.websocket.host');
        $ws_port = config('ratchet.websocket.port');
        $uri = sprintf('%s:%s', $ws_host, $ws_port);

        return app(Server::class, [
            'uri' => $uri,
            'loop' => $this->loop
        ]);
    }

    /**
     * Create HTTP server.
     * 
     * @return \Ratchet\Http\HttpServer
     */
    private function httpServer(): HttpServer
    {
        return app(HttpServer::class, ['component' => $this->ws_component]);
    }

    /**
     * Create I/O server.
     * 
     * @return \Ratchet\Server\IoServer
     */
    private function ioServer(): IoServer
    {
        return app(IoServer::class, [
            'app' => $this->http_server,
            'socket' => $this->ws_server
        ]);
    }    
}