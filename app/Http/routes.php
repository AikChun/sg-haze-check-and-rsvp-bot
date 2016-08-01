<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('bot/' . env('TELEGRAM_BOT_TOKEN') . '/getupdates', 'TelegramController@replyToMessages');
Route::get('hazebot/' . env('TELEGRAM_BOT_TOKEN') . '/webhook', 'TelegramController@setWebhook');
Route::get('hazebot/' . env('TELEGRAM_BOT_TOKEN') . '/remove_webhook', 'TelegramController@removeWebhook');
Route::post('hazebot/' . env('TELEGRAM_BOT_TOKEN') . '/webhook', 'TelegramController@webhook');


