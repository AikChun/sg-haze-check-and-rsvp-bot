<?php

use Telegram\Bot\Objects\Update;
use Telegram\Bot\Objects\User;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Chat;
use App\Bots\Commands\RsvpBot\CreateEventCommand;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateEventCommandTest extends TestCase
{
    use DatabaseTransactions;
    protected $update;
    protected $createEventCommand;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->update = factory(Update::class)->make();
        $this->createEventCommand = new CreateEventCommand;


        $chat = new Chat([
            'id'    => 1,
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
            'from'       => new User([ 'id' => 60875961, 'first_name' => 'Aik Chun', 'username' => 'EggyMcEggface' ]),
            'chat'       => new Chat([ 'id' => 1, 'title' => 'Bot SandBox', 'type'  => 'supergroup', ]),
            'date'       => 1471279851,
        ]);

        $this->update = new Update([
            'id'      => 446324986,
            'message' => $message,
        ]);
    }
    public function testReplyToUser()
    {

        $expected   = "Event: Breakfast at Winterfell is still going on.\n";
        $expected   .= "Enter your next event, Or /cancel this action.";

        $this->assertEquals($expected, $this->createEventCommand->replyToUser($this->update));
    }
}
