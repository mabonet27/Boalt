<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

 // all routers protected by Auth middleware
Route::middleware(['auth'])->group(function () {
   
    

    
});

//return the current user authenticated
Route::get('/user', function (Request $request) {
    return $request->user();
});

//Resource route for users
Route::resource('users', 'UserController');

//Resource route for notifications
Route::resource('notification', 'NotificationController');

//Route to connect with AmazonLex ChatBot
Route::post('user-text', 'AmazonLexController@postTextMessage');

 





