<?php

namespace App\Services;

class User
{
    public function getOnlineUsers($clients)
    {
        $onlineUsers = [];

        foreach ($clients as $client) {
            array_push($onlineUsers, $client->user);
        }

        return $onlineUsers;
    }
}

