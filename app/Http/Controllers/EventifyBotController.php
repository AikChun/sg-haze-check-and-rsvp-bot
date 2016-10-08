<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Log;
use Telegram;
use Telegram\Bot\Api;
use Telegram\Bot\Actions;
use App\Bots\Commands\HazeBot\StartCommand;
use App\Bots\QuestionProcessor\QuestionProcessor;
use App\Attendee;
use App\Event;
use Telegram\Bot\Objects\Update;

class EventifyBotController extends Controller
{
    protected $telegram;
    protected $questionProcessor;

    public function __construct()
    {
        $this->telegram = new Api(env('EVENTIFYBOT_TOKEN'));
        $this->telegram->addCommands([
            \App\Bots\Commands\EventifyBot\CreateEventCommand::class,
            \App\Bots\Commands\EventifyBot\ViewEventCommand::class,
            //\App\Bots\Commands\EventifyBot\DeleteEventCommand::class,
            //\App\Bots\Commands\EventifyBot\AttendingCommand::class,
            //\App\Bots\Commands\EventifyBot\CoupleCommand::class,
            //\App\Bots\Commands\EventifyBot\FriendCommand::class,
            //\App\Bots\Commands\EventifyBot\WithdrawCommand::class,
        ]);

        $this->questionProcessor = new QuestionProcessor($this->telegram);
    }

    public function setWebhook()
    {
        $response = $this->telegram->setWebhook(['url' => 'https://pickira.com/eventifybot/' . env('EVENTIFYBOT_TOKEN') . '/webhook']);
        return $response;
    }

    public function webhook()
    {
        $update = $this->telegram->commandsHandler(true);

        $message = $update->getMessage();

        // First, check the question that the update was replying to
        if($message->getReplyToMessage() == null) {

            return response()->json(["status" => "success"]);
        }

        $this->questionProcessor->addQuestions([
            //new \App\Bots\QuestionProcessor\EventifyBot\CreateEventQuestion('What is your event?', 'event.create')
        ]);

        $this->telegram->sendChatAction(['chat_id' => $message->getChat()->getId(), 'action' => Actions::TYPING]);

        $this->questionProcessor->process($message);

        return response()->json(["status" => "success"]);
    }

    public function removeWebhook()
    {
        $response = $this->telegram->removeWebhook();
        return $response;
    }
}
