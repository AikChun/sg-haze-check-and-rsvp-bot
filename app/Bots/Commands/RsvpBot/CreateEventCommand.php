<?php

namespace App\Bots\Commands\RsvpBot;

use App\Bots\Commands\CommandsUtil;
use App\Bots\UtilityClasses\RsvpBotUtility;
use App\Event;
use Illuminate\Support\Facades\Redis;
use Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Objects\Update;

class CreateEventCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "createevent";

    /**
     * @var string Command Description
     */
    protected $description = "Create an event for your group chat";

    public static $question = "What is your event?";

    public static $step = "event.create";
    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $update = $this->getUpdate();

        $text = $this->replyToUser($update);

        $forceReply = $this->getTelegram()->forceReply(['force_reply' => true, 'selective' => true]);

        Redis::set(RsvpBotUtility::retrieveFromUser($update)->getId(), self::$step); // tag user's id with status of event.create

        $this->replyWithMessage(['text' => $text, 'reply_to_message_id' => $this->getUpdate()->getMessage()->getMessageId(), 'reply_markup' => $forceReply]);

        // This will update the chat status to typing...
    }

    public function replyToUser(Update $update)
    {
        $message   = $update->getMessage();
        $messageId = $message->getMessageId();
        $chatId    = $message->getChat()->getId();

        if (RsvpBotUtility::chathasEvent($message)) {
            $text   = "You already have an event created! /delete before starting a new one.";
        } else {
            $text   = self::$question;
        }

        return $text;
    }
}
