<?php

namespace App\Bots\Commands\RsvpBot;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Objects\Update;
use App\Bots\UtilityClasses\RsvpBotUtility;
use Illuminate\Support\Facades\Redis;

class CancelCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "cancel";

    /**
     * @var string Command Description
     */
    protected $description = "Cancel the current action process";

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

        $update = $this->getUpdate();

        $text = $this->replyToUser($update);

        $fromUser = RsvpBotUtility::retrieveFromUser($update);
        if (Redis::exists($fromUser->getId())) {
            Redis::del($fromUser->getId());
        }
        // This will prepare a list of available commands and send the user.
        // First, Get an array of all registered commands
        // They'll be in 'command-name' => 'Command Handler Class' format.

        // Reply with the commands list
        $forceReply = $this->getTelegram()->forceReply(['force_reply' => false, 'selective' => true]);
        $this->replyWithMessage(['text' => $text, 'reply_to_message_id' => $this->getUpdate()->getMessage()->getMessageId(), 'reply_markup' => $forceReply]);
    }

    public function replyToUser(Update $update)
    {

        return 'Action cancelled. Thank you for using AttendAnEvent. Use /start to see what commands you can do.';
    }

}
