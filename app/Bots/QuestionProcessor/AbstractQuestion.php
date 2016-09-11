<?php

namespace App\Bots\QuestionProcessor;

use Redis;

abstract class AbstractQuestion {
    protected $question;
    protected $status;

    abstract public function handle($message);

    abstract private function announceAfterHandling($data);

    public function __construct($question, $status)
    {
        $this->question = $question;
        $this->status   = $status;
    }

    public function getQuestion() {
        return $this->question;
    }

    private function validate($message)
    {
        return (
            $this->validateUserStatus($message->getFrom()->getId())
            && $this->question == $message->getReplyToMessage()->getText()
        );
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
