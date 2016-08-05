<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Telegram;
use Telegram\Bot\Api;
use App\Bots\Commands\HazeBot\StartCommand;

class RsvpBotController extends Controller
{

    protected $telegram;

    public function __construct()
    {
        $this->telegram = new Api(env('RSVPBOT_TOKEN'));
        $this->telegram->addCommands([
            StartCommand::class,
            Telegram\Bot\Commands\HelpCommand::class
        ]);
    }

    public function setWebhook()
    {
        $response = $this->telegram->setWebhook(['url' => 'https://pickira.com/rsvpbot/' . env('RSVPBOT_TOKEN') . '/webhook']);
        return $response;
    }

    public function webhook()
    {
        $update = $this->telegram->commandsHandler(true);

        return response()->json(["status" => "success"]);
    }

    public function removeWebhook()
    {
        $response = $this->telegram->removeWebhook();
        return $response;
    }




}
