<?php

use App\Bots\Commands\RsvpBot\AttendingCommand;

use Telegram\Bot\Objects\User;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\Update;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AttendingCommandTest extends TestCase
{
    use DatabaseTransactions;

    protected $update;
    protected $message;
    protected $chat;
    protected $fromUser;
    protected $attendingCommand;
    public function setUp()
    {
        parent::setUp();
        $this->fromUser = new User([
            'id'         => 60875961,
            'first_name' => 'Aik Chun',
            'username'   => 'EggyMcEggface'
        ]);

        $this->chat = new Chat([
            'id'    => 3,
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

        $this->attendingCommand = new AttendingCommand;

    }

    public function testReplyToUser()
    {

        $expected =  'Event: '. "\n";
        $expected .= 'Dinner at Storm\'s End' . "\n\n";
        $expected .= '3000test_user'. "\n";
        $expected .= '3001test_user'. "\n";
        $expected .= '3002test_user'. "\n";
        $expected .= 'EggyMcEggface'. "\n";
        $expected .= "\n" . 'Number of attendees: 4'. "\n";
        $expected .= 'Click here to attend!'. "\n";
        $expected .= '/attending';

        $this->assertEquals($expected, $this->attendingCommand->replyToUser($this->update));
    }


}
