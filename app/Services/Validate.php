<?php

namespace App\Services;

use App\Models\Message;
use Carbon\Carbon;

class Validate
{
    const SECOND_BETWEEN_MESSAGE = 15;

    public function validateMessage($message)
    {
        if (mb_strlen($message) > 200) {
            throw new \Exception('Длина сообщения превышает 200 символов !');
        }

        $message  = trim($message);
        $message  = stripslashes($message);

        return htmlspecialchars($message);
    }

    public function validateDate($conn)
    {
        $dateLastMessage = Message::where('user_id', $conn->user->id)->get(['id', 'created_at'])->last();

        if ($dateLastMessage) {
            $currentDate = Carbon::now();
            $timeDifference = $currentDate->timestamp - $dateLastMessage->created_at->timestamp;
        } else {
            $timeDifference = self::SECOND_BETWEEN_MESSAGE;
        }

        return $timeDifference;
    }
}

