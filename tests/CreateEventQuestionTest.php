<?php

use App\Bots\QuestionProcessor\RsvpBot\CreateEventQuestion;
use Telegram\Bot\Objects\User;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\Update;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateEventQuestionTest extends TestCase
{
    protected $update;
    protected $message;
    protected $chat;
    protected $fromUser;
    protected $createEventQuestion;
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
            'text'       => 'What is the name of your event?',
        ]);

        $this->message = new Message([
            'message_id'       => 227,
            'from'             => $this->fromUser,
            'chat'             => $this->chat,
            'date'             => 1471279851,
            'reply_to_message' => $replyToMessage,
            'text'             => 'RiverRun'
        ]);

        $this->update = new Update([
            'update_id' => 446324986,
            'message'   => $this->message,

        ]);

        $this->createEventQuestion = new CreateEventQuestion('What is the name of your event?', 'event.create');
        Redis::set($this->fromUser->getId(), 'event.create'); // tag user's id with status of event.create

    }

    public function testValidateUserStatus()
    {
        $this->assertTrue($this->createEventQuestion->validateUserStatus($this->message->getFrom()->getId()));
        //$this->assertEquals('event.create', Redis::get(60875961));
        //$createEventQuestion = new CreateEventQuestion('What is the name of your event?', 'event.create');
        //$this->assertTrue($createEventQuestion->validate($this->update));

    }

    public function testValidate()
    {
        $this->assertTrue($this->createEventQuestion->validate($this->update));

    }

    public function testHandle()
    {
        $expected = 'Event: RiverRun' . "\n\n";
        $expected .= "\n" . 'Number of attendees: 0'. "\n";
        $expected .= 'Click here to attend!'. "\n";
        $expected .= '/attending';

        $this->assertEquals($expected, $this->createEventQuestion->handle($this->update));
    }


}
