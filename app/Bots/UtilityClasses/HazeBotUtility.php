<?php

namespace app\Bots\UtilityClass;

use Telegram/Bot/Objects/Update;
use Telegram/Bot/Objects/Chat;
use Telegram/Bot/Objects/Message;

class HazeBotUtility
{
    /**
     * chatHasEvent - check if there an event already created for the chat.
     *
     * @param mixed $identifier - Could be Telegram SDK Message object, Update Object, Chat Object or just an int
     * @return boolean true if there's an event already in the chat or false otherwise.
     */
    public static function chatHasEvent($identifier)
    {
        $chatId = self::retrieveChatId($identifier);

        if($chatId == null) {
            return false;
        }

        $event = event::where('chat_id', $chatId)->count();

        return $event > 0;
    }

    public static function retrieveChatId($identifier)
    {
        $chatId = null;

        if($identifier instanceof Update) {
            $identifier = $identifier->getMessage();
        }

        if($identifier instanceof Message) {
            $identifier = $identifier->getChat();
        }

        if($identifier instanceof Chat) {
            $identifier = $identifier->getId();
        }

        if(is_int($identifier)) {
            $chatId = $identifier;
        }

        return $chatId;
    }

    public static function retrieveMessageId($identifier)
    {
        $messageId = null;

        if($identifier instanceof Update) {
            $identifier = $identifier->getMessage();
        }

        if($identifier instanceof Message) {
            $identifier = $identifier->getMessageId();
        }

        if(is_int($identifier)) {
            $messageId = $identifier;
        }

        return $messageId;
    }

    public static function retrieveMessage($identifier)
    {

        if($identifier instanceof Update) {
            $identifier = $identifier->getMessage();
        }

        if($identifier instanceof Message) {
            return $identifier;
        }

        return null;
    }

    public static function retrieveFromUser($identifier)
    {

        $message = self::retrieveMessage($identifier);

        return $message != null ? $message->getFrom() : null;
    }

    public static function getFromId($identifier)
    {
        $message = self::retrieveMessage($identifier);

        return $message != null ? $message->getFrom()->getId() : null;
    }

    public static function getFromId($identifier)
    {
        $message = self::retrieveMessage($identifier);

        return $message != null ? $message->getFrom()->getId() : null;
    }

    public static function retrieveMessageText($identifier)
    {
        $message = self::retrieveMessage($identifier);
        return $message == null ? null : $message->getText();
    }

}
