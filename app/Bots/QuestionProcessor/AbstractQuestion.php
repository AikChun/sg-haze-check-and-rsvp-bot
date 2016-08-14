<?php

namespace App\Bots\QuestionProcessor;

abstract class AbstractQuestion {
    protected $question;
    protected $status;

    abstract public function handle($message);

    public function getQuestion() {
        return $this->question;
    }
}
