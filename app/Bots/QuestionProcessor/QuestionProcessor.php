<?php

use App\Bots\QuestionProcessor;
class QuestionProcessor
{
    protected $message;
    protected $telegram;
    protected $output;
    protected $questions;

    public function __construct($telegram, $message)
    {
        $this->telegram = $telegram;
        $this->message  = $message;
    }

    public function process()
    {
        foreach($this->questions as $question) {
            if($question->getQuestion() == $this->message->getReplyToMessage()->getText()) {
                $question->handle($this->message);
                return;
            }
        }
    }

    public function addQuestions(array $questionClasses)
    {
        $this->questions = $questionClasses;
    }


}
