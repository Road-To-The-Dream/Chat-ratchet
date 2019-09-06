<?php

namespace App\Http\Controllers;

use App\Services\AuthService;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $objAuth)
    {
        $this->authService = $objAuth;
    }

    public function redirect()
    {
        return $this->authService->redirect();
    }

    public function callback()
    {
        return $this->authService->callback();
    }
}
