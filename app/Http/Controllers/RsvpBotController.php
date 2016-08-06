<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Log;
use Telegram;
use Telegram\Bot\Api;
use App\Bots\Commands\HazeBot\StartCommand;
use App\Bots\Commands\RsvpBot\CreateEventCommand;
use App\Bots\Commands\RsvpBot\DeleteEventCommand;
use App\Bots\Commands\RsvpBot\ViewEventCommand;
use App\Bots\Commands\RsvpBot\AttendingCommand;
use App\Bots\Commands\RsvpBot\CoupleCommand;
use App\Bots\Commands\RsvpBot\FriendCommand;
use App\Bots\Commands\RsvpBot\WithdrawCommand;


class RsvpBotController extends Controller
{

    protected $telegram;

    public function __construct()
    {
        $this->telegram = new Api(env('RSVPBOT_TOKEN'));
        $this->telegram->addCommands([
            StartCommand::class,
            CreateEventCommand::class,
            ViewEventCommand::class,
            DeleteEventCommand::class,
            AttendingCommand::class,
            CoupleCommand::class,
            FriendCommand::class,
            WithdrawCommand::class,
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

        Log::info(print_r($update, true));

        return response()->json(["status" => "success"]);
    }

    public function removeWebhook()
    {
        $response = $this->telegram->removeWebhook();
        return $response;
    }




}
