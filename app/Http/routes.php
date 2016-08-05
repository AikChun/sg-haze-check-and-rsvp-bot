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

Route::get('hazebot/' . env('HAZEBOT_TOKEN') . '/webhook', 'HazeBotController@setWebhook');
Route::get('hazebot/' . env('HAZEBOT_TOKEN') . '/remove_webhook', 'HazeBotController@removeWebhook');
Route::post('hazebot/' . env('HAZEBOT_TOKEN') . '/webhook', 'HazeBotController@webhook');


