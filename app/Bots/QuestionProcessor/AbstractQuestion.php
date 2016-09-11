<?php

namespace App\Bots\QuestionProcessor;

use Redis;
use Log;

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
        Log::info('this question: '. $this->question);
        Log::info('replyToMessage question: '. $message->getReplyToMessage()->getText());
        if($this->question == $message->getReplyToMessage()->getText()) {
            return $this->validateUserStatus($message->getFrom()->getId());
        }
        return false;
    }
    private function validateUserStatus($userId)
    {
        Log::info('this status: '. $this->status);
        Log::info('cache status: '. Redis::get($userId));
        if($this->status != Redis::get($userId)) {
            return false;
        }
        Redis::del($userId);
        return true;
    }
}
