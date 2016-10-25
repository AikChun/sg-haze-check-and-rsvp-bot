<?php

namespace App\Bots\Commands\RsvpBot;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Objects\Update;
use App\Event;
use Illuminate\Support\Facades\Redis;
use Log;
use App\Bots\Commands\CommandsUtil;
use App\Bots\UtilityClasses\RsvpBotUtility;

class NewCreateEventCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "newcreateevent";

    /**
     * @var string Command Description
     */
    protected $description = "Create An Event for your group chat";

    public static $question = "What is the name of your event?";

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

        $update    = $this->getUpdate();

        $reply = $this->replyToUser($update);

        $forceReply = $this->getTelegram()->forceReply(['force_reply' => true, 'selective' => true]);
        $this->replyWithMessage(['text' => $reply, 'reply_to_message_id' => $messageId, 'reply_markup' => $forceReply]);

        // This will update the chat status to typing...
    }

    public function replyToUser(Update $update)
    {
        $message   = RsvpBotUtility::retrieveMessage($update);
        $messageId = RsvpBotUtility::retrieveMessageId($update);
        $chatId    = RsvpBotUtility::retrieveChatId($update);

        $from = RsvpBotUtility::getFromId($message);

        Redis::set($from, self::$step); // tag user's id with status of event.create

        return self::$question;
    }

}
