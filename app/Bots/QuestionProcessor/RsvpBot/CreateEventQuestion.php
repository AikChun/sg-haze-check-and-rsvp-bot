<?php

namespace App\Bots\QuestionProcessor\RsvpBot;

use App\Bots\QuestionProcessor\AbstractQuestion;
use App\Event;
use Redis;

class CreateEventQuestion extends AbstractQuestion
{

    public function __construct($question, $status)
    {
        $this->question = $question;
        $this->status = $status;
    }

    public function handle($message)
    {
        $userStatus = Redis::get($message->getFrom()->getId());

        if($this->status != $userStatus) {
            return 'Invalid request';
        }

        $chatId = $message->getChat()->getId();
        $event = Event::where('chat_id', $chatId)->count();

        if ($event > 0) {
            return 'There\'s already an ongoing event.';
        }

        $messageText = $message->getText();
        $event       = Event::where('chat_id', $chatId)->count();
        $event       = new Event;

        $event->chat_id     = $chatId;
        $event->description = $messageText;

        $event->save();

        Redis::del($message->getFrom()->getId());
        return $this->announceEventCreated($messageText);
    }

    private function announceEventCreated($data)
    {
        $text = "Event: \n";
        $text .= $data . "\n\n";
        $text .= "Click here to attend!\n";
        $text .= "/attending";

        return $text;
    }
}
