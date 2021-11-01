<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\ZMQ\Context;
use React\Socket\Server;
use Ratchet\Wamp\WampServer;
use App\Services\Pusher;

class StartServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $pusher = new Pusher();
        $context = new Context($loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        
        $pull->bind('tcp://127.0.0.1:5555');
        $pull->on('message', [$pusher, 'onOrderEntry']);
        
        $webSocket = new Server('0.0.0.0:7070', $loop);
        $webServer = new IoServer(
            new HttpServer(
                new WsServer(
                    new WampServer(
                        $pusher
                    )
                )
            ),
            $webSocket
        );
        
        $loop->run();

        return Command::SUCCESS;
    }
}
