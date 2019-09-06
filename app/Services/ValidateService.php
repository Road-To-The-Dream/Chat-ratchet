<?php

namespace App\Services;

use App\Models\Message;
use Carbon\Carbon;

class ValidateService
{
    const SECOND_BETWEEN_MESSAGE = 15;

    public function validateMessage($message)
    {
        $message  = trim($message);

        if (mb_strlen($message) > 20) {
            throw new \Exception('Длина сообщения превышает 200 символов !');
        }

        $message  = stripslashes($message);

        return htmlspecialchars($message);
    }

    public function validateDate($conn)
    {
        if ($this->getTimeDifferenceMessage($conn) < self::SECOND_BETWEEN_MESSAGE) {
            throw new \Exception('Интервал между сообщениями 15 секунд !');
        }

        return true;
    }

    public function getTimeDifferenceMessage($conn)
    {
        $dateLastMessage = Message::where('user_id', $conn->user->id)->get(['created_at'])->last();

        if ($dateLastMessage) {
            $currentDate = Carbon::now();
            $timeDifference = $currentDate->timestamp - $dateLastMessage->created_at->timestamp;
        } else {
            $timeDifference = self::SECOND_BETWEEN_MESSAGE;
        }

        return $timeDifference;
    }
}

