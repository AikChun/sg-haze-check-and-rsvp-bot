<?php

namespace App\Bots\QuestionProcessor\EventifyBot;

use App\Bots\QuestionProcessor\AbstractQuestion;
use App\Event;
use Redis;

class DeleteEventQuestion extends AbstractQuestion
{

    /**
     * __construct
     *
     * @param String $question will be question that the class is handling/matching
     * @param String $status will be the status or state of the user, the previous command will cache tag the user with a status to ensure that this is
     * the next step of the conversation. and also to prevent this class from processing if other users are replying to the previous message.
     */
    public function __construct($question, $status)
    {
        super($question, $status);
    }

    /**
     * handle will check for status of the user.
     * The method will then return the reply message.
     *
     * @param Message $message Telegram SDK Message object
     */
    public function handle($message)
    {
        $chatId             =  $message->getChat()->getId();

        $messageText        =  $message->getText();

        $success = Event::where('chat_id', $chatId)->andWhere('description', $messageText)->delete();

        if($success) {
            $text = "You have successfully delete the event.";
        } else {
            $text = "There's no such event.";
        }

    }

    /**
     * announceAfterHandling - Just a method to prepare the reply text
     *
     * @param mixed $data from DB
     * @return String $text - the reply message
     */
    private function announceAfterHandling($data)
    {

    }
}

