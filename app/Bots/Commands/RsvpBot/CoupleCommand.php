<?php

namespace App\Bots\Commands\RsvpBot;

use Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\Event;
use App\Attendee;
use App\Bots\Commands\RsvpBot\CommandUtil;

class CoupleCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "couple";

    /**
     * @var string Command Description
     */
    protected $description = "Attend the event as a couple";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.
        $message = $this->getUpdate()->getMessage();
        $chatId       = $this->getUpdate()->getMessage()->getChat()->getId();
        $fromUser     = $this->getUpdate()->getMessage()->getFrom();

        if (!CommandUtil::chatHasEvent($message)) {
            $this->replyWithMessage(['text' => "You have no event to attend."]);
            return false;
        }

        $fromUserName = $fromUser->getFirstName();
        if ($fromUser->getUsername() != "") {
            $fromUserName = $fromUser->getUsername();
        }

        $event = Event::where('chat_id', $chatId)->first();

        $attendee = Attendee::firstOrNew(['event_id' => $event['id'], 'user_id' => $message->getFrom()->getId()]);

        $attendee['username'] = $fromUserName . ' +1';
        $attendee['counter'] = 2;

        $attendee->save();

        $text = CommandUtil::getAttendanceList($event);

        // This will update the chat status to typing...
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        // This will prepare a list of available commands and send the user.
        // First, Get an array of all registered commands
        // They'll be in 'command-name' => 'Command Handler Class' format.

        // Reply with the commands list
        $this->replyWithMessage(['text' => $text]);
    }

}
