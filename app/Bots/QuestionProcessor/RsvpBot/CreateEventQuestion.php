<?php

namespace App\Bots\QuestionProcessor\RsvpBot;

use Telegram\Bot\Objects\Update;
use App\Bots\QuestionProcessor\AbstractQuestion;
use App\Bots\UtilityClasses\RsvpBotUtility;
use App\Event;
use Illuminate\Support\Facades\Redis;

class CreateEventQuestion extends AbstractQuestion
{

    /**
     * __construct
     *
     * @param String $question will be question that the class is handling/matching
     * @param String $status will be the status or state of the user, the previous command will cache tag the user with a status to ensure that this is
     * the next step of the conversation. and also to prevent this class from processing if other users are replying to the previous message.
     */
    public function __construct($status)
    {
        parent::__construct($status);
    }

    /**
     * handle will check for status of the user.
     * The method will then return the reply message.
     *
     * @param Message $message Telegram SDK Message object
     */
    public function handle(Update $update)
    {
        if('cancel' == strtolower(RsvpBotUtility::retrieveMessageText($update))) {
            $fromUser = RsvpBotUtility::retrieveFromUser($update);

            if (Redis::exists($fromUser->getId())) {
                Redis::del($fromUser->getId());
            }
            return "Thank you. Use /start to see what other actions you can do.";
        }

        $chatId             = RsvpBotUtility::retrieveChatId($update);

        $existingEvent = \App\Event::where('chat_id', RsvpBotUtility::retrieveChatId($update))->first();

        if($existingEvent) {
            $existingEvent->delete();
        }

        $messageText        = RsvpBotUtility::retrieveMessageText($update);

        $event              = new Event;

        $event->chat_id     = $chatId;
        $event->description = $messageText;

        $event->save();

        return RsvpBotUtility::getEventDetails($event);
    }

    /**
     * announceAfterHandling - Just a method to prepare the reply text
     *
     * @param mixed $data from DB
     * @return String $text - the reply message
     */
    public function announceAfterHandling($data)
    {
        $text = "Event: \n";
        $text .= $data . "\n\n";
        $text .= "Click here to attend!\n";
        $text .= "/attending";

        return $text;
    }
}
