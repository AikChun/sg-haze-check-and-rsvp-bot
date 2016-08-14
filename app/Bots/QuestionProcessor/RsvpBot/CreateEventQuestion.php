<?php

namespace App\Bots\QuestionProcessor\RsvpBot;

use App\Bots\QuestionProcessor\Question;

class CreateEventQuestion implements Question {
    protected $question = "What's the name of your event?";

    public function __construct()
    {
        $this->question = "What's the name of your event?";
    }

    public function handle()
    {
        //code
    }

}
