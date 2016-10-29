<?php

use App\Bots\Commands\RsvpBot\NewCreateEventCommand;
use Telegram\Bot\Objects\User;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\Update;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateEventCommandTest extends TestCase
{

    protected $update;
    protected $message;
    protected $chat;
    protected $fromUser;
    protected $createEventCommand;
    public function setUp()
    {
        parent::setUp();
        $this->fromUser = new User([
            'id'         => 60875961,
            'first_name' => 'Aik Chun',
            'username'   => 'EggyMcEggface'
        ]);

        $this->chat = new Chat([
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

        $this->message = new Message([
            'message_id' => 227,
            'from'       => $this->fromUser,
            'chat'       => $this->chat,
            'date'       => 1471279851,
            'reply_to_message' => $replyToMessage,
        ]);

        $this->update = new Update([
            'update_id' => 446324986,
            'message'   => $this->message,

        ]);

        $this->createEventCommand = new NewCreateEventCommand();

    }

    public function testReplyToUser()
    {
        $expected = "What is the name of your event?";

        $this->assertEquals($expected, $this->createEventCommand->replyToUser($this->update));
    }


}
