<?php
namespace App\Bots\QuestionProcessor;

use App\Bots\QuestionProcessor\AbstractQuestion;

class QuestionProcessor
{
    protected $telegram;

    /**
     * __construct
     *
     * @param TelegramApi $telegram Telegram SDK Object
     */
    public function __construct($telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * process - run through all the question classes to get match with the message's reply to messsage text.
     * The matching class will handle the message and return a text which this class will use the telegram bot to send.
     * @param Message $message Telegram Bot SDK Message Object
     * @return void
     */
    public function process(Update $update)
    {
        $text = "Invalid request.";
        foreach ($this->questions as $question) {
            if ($question->validate($update)) {
                $text = $question->handle($message);
                break;
            }
        }
        $this->telegram->sendMessage(['chat_id' => $message->getChat()->getId(), 'text' => $text]);
    }

    /**
     * addQuestions - Adds an array of Question Objects these objects have to be instances of AbstractQuestion
     *
     * @param array $questions
     * @return void
     */
    public function addQuestions(array $questions)
    {
        foreach ($questions as $question) {
            if (!($question instanceof AbstractQuestion)) {
                throw new Exception('Object is not of AbstractQuestion');
                continue;
            }
            $this->questions[] = $question;
        }
    }
}
