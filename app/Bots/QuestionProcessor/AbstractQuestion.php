<?php

namespace App\Bots\QuestionProcessor;

use Telegram\Bot\Objects\Update;
use Illuminate\Support\Facades\Redis;

abstract class AbstractQuestion {
    protected $question;
    protected $userState;

    abstract public function handle(Update $update);

    abstract public function announceAfterHandling($data);

    public function __construct()
    {
        // Nothing to initialize
    }

    public function validate(Update $update)
    {
        $message = $update->getMessage();

        return $this->validateUseruserState($message->getFrom()->getId());
    }
    public function validateUseruserState($userId)
    {
        $userState = Redis::get($userId);
        if($this->userState != $userState) {
            return false;
        }
        Redis::del($userId);
        return true;
    }
}
