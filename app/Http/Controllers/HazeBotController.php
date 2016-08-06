<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Http\Requests;
use Telegram;
use Telegram\Bot\Api;
use App\Bots\Commands\HazeBot\StartCommand;
use App\Bots\Commands\HazeBot\ThreeHourPsiUpdateCommand;
use App\Bots\Commands\HazeBot\TwoHourForecastCommand;
use App\Bots\Commands\HazeBot\TwentyFourHourForecastCommand;

class HazeBotController extends Controller
{
    public function __construct()
    {
        $this->telegram = new Api(env('HAZEBOT_TOKEN'));
        $this->telegram->addCommands([
            StartCommand::class,
            ThreeHourPsiUpdateCommand::class,
            TwoHourForecastCommand::class,
            TwentyFourHourForecastCommand::class,
            Telegram\Bot\Commands\HelpCommand::class
        ]);
    }

    public function setWebhook()
    {
        $response = Telegram::setWebhook(['url' => 'https://pickira.com/hazebot/'. env('TELEGRAM_BOT_TOKEN'). '/webhook']);

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
    }
}
