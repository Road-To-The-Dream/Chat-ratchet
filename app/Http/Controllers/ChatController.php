<?php

namespace App\Http\Controllers;

use App\Http\ChatSocket;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }
}
