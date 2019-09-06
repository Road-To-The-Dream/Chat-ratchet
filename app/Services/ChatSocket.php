<?php

namespace App\Services;

use App\Models\User;
use App\Services\UserService as UserService;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class ChatSocket implements MessageComponentInterface
{
    protected $clients;
    private $userService;
    private $validateService;
    private $messageService;

    public function __construct(UserService $userService, ValidateService $validateService, MessageService $messageService) {
        $this->clients = new \SplObjectStorage;
        $this->userService = $userService;
        $this->validateService = $validateService;
        $this->messageService = $messageService;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $userToken = $conn->httpRequest->getUri()->getQuery();

        if (empty($userToken)) {
            $conn->close();

            return false;
        }

        $user = User::where('token', '=' , $userToken)->first();

        if (!$user || $user->isBanned()){
            $conn->close();

            return false;
        }

        $conn->user = $user;
        $this->clients->attach($conn);

        $this->refreshListUsers($this->userService->getUsersByToken($this->userService->getTokensOnlineUsers($this->clients)));

        $onlineUsers = $this->userService->getOnlineUsers($this->clients);

        $newUser = $this->userService->getUserById($conn->user->id);
        foreach ($this->clients as $client) {
            $this->sendResponse($conn->user, 'newUser', $newUser, $onlineUsers);
        }

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $conn, $msg)
    {
        if (!$conn->user->isBanned() && !$conn->user->isMuted()) {
            $data = json_decode($msg);

            if (is_null($data) || !is_object($data)) {
                $conn->close();

                return false;
            }

            if (isset($data->type) && is_string($data->type)) {
                $type = $data->type;
            } else {
                $conn->close();

                return false;
            }

            switch ($type) {
                case 'newMessage':
                    try {
                        $this->validateService->validateDate($conn);
                        $this->validateService->validateMessage($data->message);

                        $this->sendResponse($this->userService->getUserWithColor($conn->user->id),'newMessage', $data->message);

                        $this->messageService->createMessage($conn->user, $data->message);
                    } catch (\Exception $exception) {
                        $this->sendResponse($conn->user, 'error', $exception->getMessage());
                    }

                    break;

                case 'ban':
                    if ($conn->user->isAdmin() && isset($data->userId)) {
                        if (!$user = User::find($data->userId)) {
                            return false;
                        }

                        $user->isBan = boolval($data->value);
                        $user->save();

                        $this->sendResponse($conn->user, 'ban', $data->userId);
                    }
                    break;

                case 'mute':
                    if ($conn->user->isAdmin() && $data->userId) {
                        if (!$user = User::find($data->userId)) {
                            return false;
                        }

                        $user->isMute = boolval($data->value);
                        $user->save();

                        $this->sendResponse($conn->user, 'mute', $data->userId);

                        $tokens = $this->userService->getTokensOnlineUsers($this->clients);
                        $this->refreshListUsers(User::whereIn('token', $tokens)->get());
                    }
                    break;
            }
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

        $this->clients->detach($conn);
        $conn->close();
    }

    public function sendResponse($user, $type, $message, $onlineUsers = null)
    {
        foreach ($this->clients as $client) {
            $client->send(json_encode([
                "type" => $type,
                "user" => $user,
                "message" => $message,
                "onlineUsers" => $onlineUsers
            ]));
        }
    }

    public function refreshListUsers($onlineUsers)
    {
        foreach ($this->clients as $client) {
            foreach ($onlineUsers as $user) {
                if ($client->user->token === $user->token) {
                    $client->user = $user;
                }
            }
        }
    }
}
