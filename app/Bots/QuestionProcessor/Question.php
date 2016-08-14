<?php

namespace App\Bots\QuestionProcessor;

abstract class Question {
    protected $question;

    public function handle();

    public function getQuestion() {
        return $this->question;
    }
}
