<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// GoogleAuth routes
Route::get('/redirect', 'SocialAuthGoogleController@redirect');
Route::get('/callback', 'SocialAuthGoogleController@callback');
// GoogleAuth routes

//// GitHubAuth routes
//Route::get('auth/github', 'AuthController@redirect');
//Route::get('auth/github/callback', 'AuthController@callback');
//// GitHubAuth routes

Route::get('/chat', 'ChatController@index')->middleware('auth');
