<?php

namespace App\Bots\Commands\RsvpBot;

use Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\Event;
use App\Attendee;
use Telegram\Bot\Objects\Update;
use App\Bots\UtilityClasses\RsvpBotUtility;

class AttendingCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "attending";

    /**
     * @var string Command Description
     */
    protected $description = "Attend the event";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.

        // This will update the chat status to typing...
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $text = $this->replyToUser($this->getUpdate());
        // This will prepare a list of available commands and send the user.
        // First, Get an array of all registered commands
        // They'll be in 'command-name' => 'Command Handler Class' format.

        // Reply with the commands list
        $this->replyWithMessage(['text' => $text]);
    }

    public function replyToUser(Update $update)
    {
        $chatId    = RsvpBotUtility::retrieveChatId($update);

        $event = Event::where('chat_id', $chatId)->first();

        if(!$event) {
            return "You don't got no event to attend cuz.";
        }

        $user = RsvpBotUtility::retrieveFromUser($update);

        if(!$event->registerUser($user)) {
            return "Unable to register name to event cuz.";
        }

        return RsvpBotUtility::getEventDetails($event);
    }

}
