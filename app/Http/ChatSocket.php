<?php

namespace App\Http;

use App\Models\Message;
use App\Models\User;
use App\Services\User as UserService;
use App\Services\Validate;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class ChatSocket implements MessageComponentInterface
{
    protected $clients;
    private $userService;
    private $validateService;

    public function __construct(UserService $userService, Validate $validateService) {
        $this->clients = new \SplObjectStorage;
        $this->userService = $userService;
        $this->validateService = $validateService;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $userToken = $conn->httpRequest->getUri()->getQuery();

        if (empty($userToken)) {
            $conn->close();
        }

        $user = User::where('token', '=' , $userToken)->first();

        $conn->user = $user;
        $this->clients->attach($conn);

        $onlineUsers = $this->userService->getOnlineUsers($conn, $this->clients);

        foreach ($this->clients as $client) {
            $client->send(json_encode([
                "type" => 'newUser',
                "user" => $conn->user,
                "onlineUsers" => $onlineUsers
            ]));
        }

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $conn, $msg)
    {
        $data = json_decode($msg);
        $type = $data->type;

        switch ($type) {
            case 'newMessage':
                $this->validateService->validateMessage($data->message);
                foreach ($this->clients as $client) {
                    $client->send(json_encode([
                        "type" => $type,
                        "user" => json_encode($conn->user),
                        "message" => $data->message
                    ]));
                }

                $conn->user->messages()->create([
                    'text' => $data->message,
                ]);
        }

        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $conn->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        foreach ($this->clients as $client) {
            $client->send(json_encode([
                "type" => "disconnect",
                "userName" => $conn->user->name,
                "message" => "Total Connected: " . count($this->clients),
                "onlineUsers" => $this->userService->getOnlineUsers($conn, $this->clients)
            ]));
        }

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
