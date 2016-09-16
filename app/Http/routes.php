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


Route::get('hazebot/' . env('HAZEBOT_TOKEN') . '/setWebhook', 'HazeBotController@setWebhook');
Route::get('hazebot/' . env('HAZEBOT_TOKEN') . '/remove_webhook', 'HazeBotController@removeWebhook');
Route::post('hazebot/' . env('HAZEBOT_TOKEN') . '/webhook', 'HazeBotController@webhook');

Route::get('rsvpbot/' . env('RSVPBOT_TOKEN') . '/setWebhook', 'RsvpBotController@setWebhook');
Route::get('rsvpbot/' . env('RSVPBOT_TOKEN') . '/remove_webhook', 'RsvpBotController@removeWebhook');
Route::post('rsvpbot/' . env('RSVPBOT_TOKEN') . '/webhook', 'RsvpBotController@webhook');

Route::get('eventifybot/' . env('EVENTIFYBOT_TOKEN') . '/setWebhook', 'EventifyBotController@setWebhook');
Route::get('eventifybot/' . env('EVENTIFYBOT_TOKEN') . '/remove_webhook', 'EventifyBotController@removeWebhook');
Route::post('eventifybot/' . env('EVENTIFYBOT_TOKEN') . '/webhook', 'EventifyBotController@webhook');

