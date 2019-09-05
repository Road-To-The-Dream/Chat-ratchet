<?php

namespace App\Console\Commands;

use App\Http\ChatSocket;
use App\Services\User;
use App\Services\Validate;
use Illuminate\Console\Command;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory as LoopFactory;
use React\Socket\Server as Reactor;

class ChatServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat_server:serve';

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
     * @return mixed
     */
    public function handle()
    {
        $port = 8073;
        echo "Ratchet server started on port:$port \n";
        $loop = LoopFactory::create();
        $socket = new Reactor($port, $loop);
        $server = new IoServer(new HttpServer(new WsServer(new ChatSocket(new User(), new Validate()))), $socket, $loop);
        $server->run();
    }
}
