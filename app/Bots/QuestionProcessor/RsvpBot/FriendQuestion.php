<?php
namespace App\Bots\QuestionProcessor\RsvpBot;

use Telegram\Bot\Objects\Update;
use App\Bots\QuestionProcessor\AbstractQuestion;
use App\Bots\Commands\RsvpBot\CommandUtil;
use App\Event;
use App\Attendee;

class FriendQuestion extends AbstractQuestion
{

    /**
     * __construct
     *
     * @param String $question will be question that the class is handling/matching
     * @param String $status will be the status or state of the user, the previous command will cache tag the user with a status to ensure that this is
     * the next step of the conversation. and also to prevent this class from processing if other users are replying to the previous message.
     */
    public function __construct($question, $status)
    {
        parent::__construct($question, $status);
    }

    /**
     * handle will check for status of the user.
     * The method will then return the reply message.
     *
     * @param Message $message Telegram SDK Message object
     */
    public function handle(Update $update)
    {
        $message = $update->getMessage();
        $chatId = $message->getChat()->getId();
        $event = Event::where('chat_id', $chatId)->count();

        if ($event == 0) {
            return 'There\'s no event.';
        }

        $messageText = $message->getText();

        $event       = Event::where('chat_id', $chatId)->first();
        $attendee    = Attendee::firstOrNew(['event_id' => $event->id, 'username' => $messageText]);
        $attendee->save();

        return CommandUtil::getAttendanceList($event);

    }

    /**
     * announceAfterHandling - Just a method to prepare the reply text
     *
     * @param mixed $data from DB
     * @return String $text - the reply message
     */
    public function announceAfterHandling($data)
    {

    }
}
