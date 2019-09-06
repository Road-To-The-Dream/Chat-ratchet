<?php

namespace App\Services;

use App\Models\Color;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GitHubAuthService implements AuthService
{
    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function callback()
    {
        try {
            $gitHubUser = Socialite::driver('github')->stateless()->user();
            $existUser = User::where('email', $gitHubUser->email)->first();
            if ($existUser) {
                $existUser->color_id = Color::random()->id;
                $existUser->save();
                Auth::loginUsingId($existUser->id);
            } else {
                $user = new User();
                $user->name = $gitHubUser->name;
                $user->color_id = Color::random()->id;
                $user->email = $gitHubUser->email;
                $user->gravatar_img = 'http://www.gravatar.com/avatar/' . md5($gitHubUser->email) . '?d=robohash&s=50';
                $user->token = Str::random(16);
                $user->github_id = $gitHubUser->id;
                $user->save();
                Auth::loginUsingId($user->id);
            }
            return redirect()->to('/chat');
        } catch (Exception $e) {
            return 'error';
        }
    }

    private function findOrCreateUser($githubUser)
    {
        if ($authUser = User::where('github_id', $githubUser->id)->first()) {
            return $authUser;
        }

        return User::create([
            'name' => $githubUser->name,
            'email' => $githubUser->email,
            'github_id' => $githubUser->id,
            'avatar' => $githubUser->avatar
        ]);
    }
}
