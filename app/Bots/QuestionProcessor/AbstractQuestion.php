<?php

namespace App\Bots\QuestionProcessor;

use Redis;

abstract class AbstractQuestion {
    protected $question;
    protected $status;

    abstract public function handle($message);

    abstract public function announceAfterHandling($data);

    public function __construct($question, $status)
    {
        $this->question = $question;
        $this->status   = $status;
    }

    public function getQuestion() {
        return $this->question;
    }

    public function validate($message)
    {
        if($this->question == $message->getReplyToMessage()->getText()) {
            return $this->validateUserStatus($message->getFrom()->getId());
        }
        return false;
    }
    private function validateUserStatus($userId)
    {
        if($this->status != Redis::get($userId)) {
            return false;
        }
        Redis::del($userId);
        return true;
    }
}
