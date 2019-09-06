<?php

namespace App\Services;

use App\Models\Color;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuth implements Auth
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $existUser = User::where('email', $googleUser->email)->first();
            if ($existUser) {
                $existUser->color_id = Color::random()->id;
                $existUser->save();
                Auth::loginUsingId($existUser->id);
            } else {
                $user = new User();
                $user->name = $googleUser->name;
                $user->color_id = Color::random()->id;
                $user->email = $googleUser->email;
                $user->google_id = $googleUser->id;
                $user->save();
                Auth::loginUsingId($user->id);
            }
            return redirect()->to('/chat');
        } catch (Exception $e) {
            return 'error';
        }
    }
}
