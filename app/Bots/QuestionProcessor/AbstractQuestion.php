<?php

namespace App\Bots\QuestionProcessor;

abstract class AbstractQuestion {
    protected $question;

    public function handle();

    public function getQuestion() {
        return $this->question;
    }
}
