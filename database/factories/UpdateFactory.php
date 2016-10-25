<?php

use Telegram\Bot\Objects\User;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\Update;

$factory->define(Update::class, function () {

        $fromUser = new User([
            'id'         => 60875961,
            'first_name' => 'Aik Chun',
            'username'   => 'EggyMcEggface'
        ]);

        $chat = new Chat([
            'id'    => -1001053768020,
            'title' => 'Bot SandBox',
            'type'  => 'supergroup',
        ]);

        $replyToMessageFromUser = new User([
            'id'         => 259765048,
            'first_name' => 'RSVPMyAss',
            'username'   => 'RSVPMyAss'
        ]);

        $replyToMessageChat = new Chat([
            'id' => -1001053768020,
            'title' => 'Bot test sandbox',
            'type' => 'supergroup',
        ]);

        $replyToMessage = new Message([
            'message_id' => 226,
            'from'       => $replyToMessageFromUser,
            'chat'       => $replyToMessageChat,
            'date'       => 1471279851,
            'text'       => 'EggyMcEggface',
        ]);

        $message = new Message([
            'message_id' => 227,
            'from'       => $fromUser,
            'chat'       => $chat,
            'date'       => 1471279851,
            'reply_to_message' => $replyToMessage,
        ]);

    return [
        'update_id' => 446324986,
        'message'   => $message,
    ];
});
