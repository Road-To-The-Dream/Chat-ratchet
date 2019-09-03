<?php

namespace App\Services;

interface Auth
{
    public function redirect();

    public function callback();
}
