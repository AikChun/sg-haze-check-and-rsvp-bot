<?php

namespace App\Bots\Commands\RsvpBot;

use Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\Event;
use App\Attendee;
use Redis;

class FriendCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "friend";

    /**
     * @var string Command Description
     */
    protected $description = "Add a friend to the event";

    public static $question = "What is your friend's name?";
    public static $step = "friend.add";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.
        $message  = $this->getUpdate()->getMessage();
        $chatId   = $this->getUpdate()->getMessage()->getChat()->getId();
        $fromUser = $this->getUpdate()->getMessage()->getFrom();

        $event = Event::where('chat_id', $message->getChat()->getId())->first();
        if (!$event) {
            $this->replyWithMessage(['text' => "You don't got no event to attend cuz."]);
            return false;
        }

        Redis::set($message->getFrom()->getId(), self::$step); // tag user's id with status of friend.add

        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $forceReply = $this->getTelegram()->forceReply(['force_reply' => true, 'selective' => true]);

        $this->replyWithMessage(['text' => self::$question, 'reply_to_message_id' => $message->getMessageId(), 'reply_markup' => $forceReply]);

    }
}
