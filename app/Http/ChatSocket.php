<?php

namespace App\Http;

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

        $tokens = $this->getTokensOnlineUsers();
        $on = User::whereIn('token', $tokens)->get();
        foreach ($this->clients as $client) {
            foreach ($on as $user) {
                if ($client->user->token === $user->token) {
                    $client->user = $user;
                }
            }
        }

        $onlineUsers = $this->userService->getOnlineUsers($this->clients);

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
        $user = User::findOrFail($data->userToken);

        switch ($type) {
            case 'newMessage':
                try {
                    $timeDifference = $this->validateService->validateDate($conn) >= Validate::SECOND_BETWEEN_MESSAGE;

                    if (!$conn->user->isBanned() && !$conn->user->isMuted() && $timeDifference) {
                        $this->validateService->validateMessage($data->message);

                        $this->sendResponse($conn,'newMessage', $data->message);

                        $conn->user->messages()->create([
                            'text' => $data->message,
                        ]);
                    }
                } catch (\Exception $exception) {
                    $this->sendResponse($conn, 'error', $exception->getMessage());
                }

                break;
            case 'ban':
                if ($conn->user->isAdmin()) {
                    $user->isBan = $data->value;
                    $user->save();

                    $this->sendResponse($conn, 'ban', $data->userToken);
                }
                break;
            case 'mute':
                if ($conn->user->isAdmin()) {
                    $user->isMute = $data->value;
                    $user->save();

                    $this->sendResponse($conn, 'mute', $data->userToken);
                }
                break;
        }

        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $conn->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);

        $onlineUsers = $this->userService->getOnlineUsers($this->clients);

        foreach ($this->clients as $client) {
            $client->send(json_encode([
                "type" => "disconnect",
                "userName" => $conn->user->name,
                "message" => "Total Connected: " . count($this->clients),
                "onlineUsers" => $onlineUsers
            ]));
        }

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    public function sendResponse($conn, $type, $message)
    {
        $user = json_encode(User::with('color')->where('id', $conn->user->id)->first());

        foreach ($this->clients as $client) {
            $client->send(json_encode([
                "type" => $type,
                "user" => $user,
                "message" => $message
            ]));
        }
    }

    public function getTokensOnlineUsers()
    {
        $tokens = [];

        foreach ($this->clients as $client) {
            array_push($tokens, $client->user->token);
        }

        return $tokens;
    }
}
