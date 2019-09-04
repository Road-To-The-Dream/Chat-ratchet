<?php

namespace App\Services;

class Validate
{
    public function validateMessage($message)
    {
        if (strlen($message) > 200) {
            return 'Длина сообщения превышает 200 символов !';
        }

        $message  = trim($message);
        $message  = stripslashes($message);

        return htmlspecialchars($message);
    }

    public function validateDate()
    {

    }
}

