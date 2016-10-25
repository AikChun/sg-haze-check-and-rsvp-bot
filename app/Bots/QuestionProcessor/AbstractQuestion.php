<?php

namespace App\Bots\QuestionProcessor;

use Telegram\Bot\Objects\Update;
use Illuminate\Support\Facades\Redis;

abstract class AbstractQuestion {
    protected $question;
    protected $status;

    abstract public function handle(Update $update);

    abstract public function announceAfterHandling($data);

    public function __construct($question, $status)
    {
        $this->question = $question;
        $this->status   = $status;
    }

    public function getQuestion() {
        return $this->question;
    }

    public function validate(Update $update)
    {
        $message = $update->getMessage();

        if($this->question == $message->getReplyToMessage()->getText()) {
            return $this->validateUserStatus($message->getFrom()->getId());
        }
        return false;
    }
    public function validateUserStatus($userId)
    {
        $status = Redis::get($userId);
        if($this->status != $status) {
            return false;
        }
        Redis::del($userId);
        return true;
    }
}
