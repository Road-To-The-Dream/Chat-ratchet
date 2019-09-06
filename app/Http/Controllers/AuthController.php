<?php

namespace App\Http\Controllers;

use App\Services\Auth;

class AuthController extends Controller
{
    private $authService;

    public function __construct(Auth $objAuth)
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
