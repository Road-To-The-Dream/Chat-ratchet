<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        $messages = json_encode(DB::table('messages')
            ->join('users', 'messages.user_id', '=', 'users.id')
            ->join('colors', 'users.color_id', '=', 'colors.id')
            ->select('messages.text', 'users.name as user_name', 'users.gravatar_img', 'messages.created_at', 'colors.name as user_color')
            ->orderBy('created_at', 'asc')
            ->get());

        if (Auth::user()->isAdmin()) {
            return view('chat')->with([
                'user' => User::where('id', Auth::user()->id)->first(['token']),
                'messages' => $messages,
                'user' => Auth::user(),
                'members' => json_encode(User::where('role', '!=', 'admin')->toBase()->get(['id', 'name', 'role', 'isBan', 'isMute', 'gravatar_img']))
            ]);
        } else {
            return view('chat')->with([
                'user' => User::where('id', Auth::user()->id)->first(['token']),
                'messages' => $messages,
                'user' => Auth::user()
            ]);
        }
    }
}
