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
use App\Services\Ratchet;

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
        $ratchet = new Ratchet();
        $context = new Context($loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        
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
