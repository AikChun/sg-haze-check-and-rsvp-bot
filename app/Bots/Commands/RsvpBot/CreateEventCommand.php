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
    protected $name                    = "createevent";

    /**
     * @var string Command Description
     */
    protected $description             = "Create an event for your group chat";

    protected $reply;

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

        $replyParams = $this->replyToUser($this->getUpdate());

        $this->replyWithMessage($replyParams);

    }

    public function replyToUser(Update $update)
    {
        if (Event::where('chat_id', $update->getMessage()->getChat()->getId())->first()) {
            $this->reply = new CreateEventReply();
            $step = 'event.create';
        } else {
            $this->reply = new CreateOrCancelEventReply();
            $step = 'event.createOrCancel';
        }

        Redis::set($update->getMessage()->getFrom()->getId(), $step); // tag user's id with status of event.createOrCancel

        $this->reply->process($update);

        return $this->reply->getReplyParams();
    }

}
