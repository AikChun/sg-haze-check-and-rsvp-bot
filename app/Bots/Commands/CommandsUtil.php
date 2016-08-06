<?php

namespace App\Bots\Commands;

class CommandsUtil
{
    public static function getMessageChatType($message)
    {
        Log::info(print_r($message->getChat(), true));
    }
}
