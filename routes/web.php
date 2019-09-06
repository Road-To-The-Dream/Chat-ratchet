<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// GoogleAuth routes
Route::get('/redirect', 'AuthController@redirect');
Route::get('/callback', 'AuthController@callback');
// GoogleAuth routes

// GitHubAuth routes
Route::get('auth/github', 'AuthController@redirect');
Route::get('auth/github/callback', 'AuthController@callback');
// GitHubAuth routes

Route::get('/chat', 'ChatController@index')->middleware('auth');
