<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getOnlineUsers($clients)
    {
        $onlineUsers = [];

        foreach ($clients as $client) {
            array_push($onlineUsers, $client->user);
        }

        return $onlineUsers;
    }

    public function getTokensOnlineUsers($clients)
    {
        $tokens = [];

        foreach ($clients as $client) {
            array_push($tokens, $client->user->token);
        }

        return $tokens;
    }

    public function getUsersByToken($tokens)
    {
        return User::whereIn('token', $tokens)->get();
    }

    public function getUserById($id)
    {
        return User::where('id', $id)->select(['id', 'name', 'role', 'gravatar_img'])->toBase()->first();
    }

    public function getUserWithColor($id)
    {
        return User::with('color')->where('id', $id)->first();
    }
}

