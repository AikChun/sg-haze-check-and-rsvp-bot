<?php

use App\Bots\Commands\RsvpBot\CancelCommand;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Redis;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Objects\User;

class CancelCommandTest extends TestCase
{
    use DatabaseTransactions;

    protected $update;
    protected $message;
    protected $chat;
    protected $fromUser;
    protected $cancelCommand;
    public function setUp()
    {
        $this->cancelCommand = new CancelCommand;
    }

    public function testReplyToUser()
    {
        //Redis::set('123', 'event.create');

        //$this->assertEquals('event.create', Redis::get('123'));

        //$this->assertNotEquals('event.create', Redis::get('234'));

        $message = new Message([
            'message_id' => 227,
            'from'       => new User(['id' => 123, 'first_name' => 'Aik Chun', 'username'   => 'EggyMcEggface' ]),
            'chat'       => new Chat(['id' => -1001053768020, 'title' => 'Bot SandBox', 'type' => 'supergroup']),
            'date'       => 1471279851,
        ]);

        $update = new Update([
            'update_id' => 12345678,
            'message'   => $message
        ]);

        $secondMessage = new Message([
            'message_id' => 227,
            'from'       => new User(['id' => 234, 'first_name' => 'egg', 'last_name' => 'man', 'username' => 'eggman']),
            'chat'       => new Chat(['id' => 1010, 'title' => 'test chat', 'type' => 'supergroup']),
            'date'       => 1471279851,
        ]);

        $secondUpdate = new Update([
            'update_id' => 1234,
            'message'   => $secondMessage,
        ]);

        $expected = 'Action cancelled. Thank you for using AttendAnEvent. Use /start to see what commands you can do.';

        $this->assertEquals($expected, $this->cancelCommand->replyToUser($update));

        $this->assertEquals($expected, $this->cancelCommand->replyToUser($secondUpdate));

    }


}
