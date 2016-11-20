<?php

namespace App\Bots\QuestionProcessor\RsvpBot;

use Telegram\Bot\Objects\Update;
use App\Bots\QuestionProcessor\AbstractQuestion;
use App\Bots\UtilityClasses\RsvpBotUtility;
use App\Event;
use Illuminate\Support\Facades\Redis;

class CreateEventQuestion extends AbstractQuestion
{
    protected $userState = 'event.create';

    /**
     * handle will check for status of the user.
     * The method will then return the reply message.
     *
     * @param Message $message Telegram SDK Message object
     */
    public function handle(Update $update)
    {
        // create & save event
        $event = new Event;

        $message = $update->getMessage();
        $chat    = $message->getChat();

        $event = Event::create([
            'description' => $message->getText(),
            'chat_id'     => $chat->getId();
        ]);
        // set fields for event
        return $event->getEventDetails();
    }
}
