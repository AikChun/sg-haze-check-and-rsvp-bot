<?php
namespace App\Bots\QuestionProcessor;
use App\Bots\QuestionProcessor\AbstractQuestion;
class QuestionProcessor
{
    protected $telegram;

    public function __construct($telegram)
    {
        $this->telegram = $telegram;
    }

    public function process($message)
    {
        foreach($this->questions as $question) {
            if($question->getQuestion() == $message->getReplyToMessage()->getText()) {
                $text = $question->handle($message);
                $this->telegram->sendMessage(['chat_id' => $message->getChat()->getId(), 'text' => $text]);
                return;
            }
        }

    }

    public function addQuestions(array $questions)
    {
        foreach($questions as $question) {
            if(!($question instanceof AbstractQuestion)) {
                throw new Exception('Object is not of AbstractQuestion');
                continue;
            }
            $this->questions[] = $question;
        }
    }


}
