<?php

namespace App\Bots\QuestionProcessor\RsvpBot;

use Telegram\Bot\Objects\Update;
use App\Bots\QuestionProcessor\AbstractQuestion;
use App\Bots\UtilityClasses\RsvpBotUtility;
use App\Event;
use Illuminate\Support\Facades\Redis;

class CreateEventQuestion extends AbstractQuestion
{
    protected $update;
    protected $userState = 'event.createOrCancel';
    protected $reply;

    /**
     * handle will check for status of the user.
     * The method will then return the reply message.
     *
     * @param Message $message Telegram SDK Message object
     */
    public function handle(Update $update)
    {
        $this->update = $update;
        // create & save event
        // determine whether user chose create new event, cancel or other wise
        $this->checkUserReply(strtolower($this->update->getMessage()->getText()));

        return $this->reply;
    }

    public function checkUserReply($text)
    {
        if('cancel' === $text) {
            // tell user he has cancel this action.
            Redis::del($this->update->getMessage()->getFrom()->getId());
            $this->reply = "Action is cancelled.";
        } else if ('create new event' === $text) {
            Redis::set($this->update->getMessage()->getFrom()->getId(), 'event.create');
            $this->reply = "";
            // set redis with user id as key and event.create as value
            // send user message to ask for event description
        } else {
            $this->reply = "Reply not recognized. Please choose either to create or cancel.";
            // tell user bot don't recognize input
            // reinstate userState in redis as createOrCancel
        }
    }


}
