<?php

namespace App\Services;

interface AuthService
{
    public function redirect();

    public function callback();
}
