<?php

namespace App\Bots\Commands;

use Log;
class CommandsUtil
{
    public static function getMessageChatId($message)
    {
        Log::info(print_r($message->getChat(), true));
        if($message->getChat()->getType() == 'supergroup') {
            return $message->getChat()->getId();
        }

        return $message->getChat()->getId();
    }
}
