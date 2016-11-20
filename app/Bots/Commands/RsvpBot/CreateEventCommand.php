<?php

namespace App\Bots\Commands\RsvpBot;

use App\Bots\Commands\CommandsUtil;
use App\Bots\UtilityClasses\RsvpBotUtility;
use App\Event;
use Illuminate\Support\Facades\Redis;
use Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Objects\Update;

class CreateEventCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name                    = "createevent";

    /**
     * @var string Command Description
     */
    protected $description             = "Create an event for your group chat";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        // This will update the chat status to typing...
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        // find out if there's existing event
        $chatId = $this->getUpdate()->getMessage()->getChat()->getId();

        $replyParameters = []; // initialize parameters for reply
        $userState = ''; // initialize user state to be save in cache

        $currentEvent = Event::where('chat_id', $chatId)->first(); // Find existing event for chat

        if($currentEvent) { // if there's an exisitng event for chat
            $replyParameters = $this->buildReplyParametersForExistingEvent();
            $userState       = 'event.createOrCancel';
        } else {
            $replyParameters = $this->buildReplyParametersForNewEvent();
            $userState       = 'event.create';
        }

        $fromUserId = $this->getUpdate()->getMessage()->getFrom()->getId();

        $this->setUserStateInCache($fromUserId, $userState);

        $this->replyWithMessage($replyParameters);

    }

    /**
     * Builds reply message parameters when the chat has an existing event
     */
    protected function buildReplyParametersForExistingEvent()
    {
        $keyboard => [
            'Create New Event',
            'Cancel'
        ];

        $replyMarkup = $this->telegram->replyKeyboardMarkup([
            'keyboard'          => $keyboard,
            'resize_keyboard'   => true,
            'one_time_keyboard' => true,
            'selective'         => true,
            'force_reply'       => true,
        ]);

        $replyText =  'You have an existing event\nChoose to either **Create New Event** or **Cancel** this action.';

        return [
            'text'         => $text,
            'reply_markup' => $replyMarkup,
        ];
    }

    /**
     * Builds reply parameters when chat has no existing event
     */
    protected function buildReplyParametersForNewEvent()
    {
            $replyText = 'What is your event?';

            $replyMarkup = [
                'selective'   => true,
                'force_reply' => true
            ];

        return [
            'text'         => $text,
            'reply_markup' => $replyMarkup,
        ];
    }

    /**
     * Saves user's id as key and user state as value with redis.
     */
    protected function setUserStateInCache($userId, $userState)
    {
        Redis::set($userId, $userState);
        Redis::expire($userId, 2);
    }

}
