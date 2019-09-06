<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        if (Auth::user()->isBanned()) {
            return view('ban');
        }

        $messages = DB::table('messages')
            ->join('users', 'messages.user_id', '=', 'users.id')
            ->join('colors', 'users.color_id', '=', 'colors.id')
            ->select('messages.text', 'users.name as user_name', 'users.gravatar_img', 'messages.created_at', 'colors.name as user_color')
            ->orderBy('created_at', 'asc')
            ->limit(20)
            ->get();

        return view('chat')->with([
            'messages' => $messages,
            'user' => Auth::user(),
            'members' => Auth::user()->isAdmin() ? User::where('role', '!=', 'admin')->toBase()->get(['id', 'name', 'role', 'token', 'isBan', 'isMute', 'gravatar_img']) : null
        ]);
    }
}
