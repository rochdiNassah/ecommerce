<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Zmq\Context;
use React\Socket\Server;
use Ratchet\Wamp\WampServer;
use App\Services\Ratchet;
use Zmq;

class StartServerCommand extends Command
{
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
        $loop = Factory::create();
        $ratchet = app(Ratchet::class);
        $context = new Context($loop);
        $pull = $context->getSocket(Zmq::SOCKET_PULL);
        
        $pull->bind('tcp://0.0.0.0:1111');
        $pull->on('message', [$ratchet, 'onOrderEntry']);
        
        $webSocket = new Server('0.0.0.0:1112', $loop);
        $webServer = new IoServer(
            new HttpServer(
                new WsServer(
                    new WampServer(
                        $ratchet
                    )
                )
            ),
            $webSocket
        );
        
        $loop->run();

        return Command::SUCCESS;
    }
}
