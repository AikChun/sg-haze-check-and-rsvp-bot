<?php

namespace App\Bots\QuestionProcessor;

abstract class AbstractQuestion {
    protected $question;
    protected $status;

    public function handle($message);

    public function getQuestion() {
        return $this->question;
    }
}
