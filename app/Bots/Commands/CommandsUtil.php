<?php

namespace App\Bots\Commands;

use Log;
class CommandsUtil
{
    public static function getMessageChatType($message)
    {
        Log::info(print_r($message->getChat(), true));
    }
}
