<?php

namespace App\Services;

class MessageService
{
    public function createMessage($user, $message)
    {
        $user->messages()->create([
            'text' => $message,
        ]);
    }
}
