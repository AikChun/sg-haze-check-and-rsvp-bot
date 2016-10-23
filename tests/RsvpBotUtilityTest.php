<?php

use App\Bots\UtilityClasses\RsvpBotUtility;
use Telegram\Bot\Objects\User;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\Update;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RsvpBotUtilityTest extends TestCase
{
    protected $update;
    protected $message;
    protected $chat;
    protected $fromUser;
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

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testChatHasEvent()
    {
        $this->assertTrue(RsvpBotUtility::chatHasEvent(1));
        $this->assertFalse(RsvpBotUtility::chatHasEvent(10));
    }

    public function testRetrieveChatId()
    {
        $this->assertEquals(-1001053768020, RsvpBotUtility::retrieveChatId($this->update));
        $this->assertNotEquals(-1001053768029, RsvpBotUtility::retrieveChatId($this->update));
    }

    public function testRetrieveMessage()
    {
        $this->assertTrue(RsvpBotUtility::retrieveMessage($this->update) instanceof Message);
        $this->assertTrue(RsvpBotUtility::retrieveMessage($this->message) instanceof Message);
        $this->assertTrue(null == RsvpBotUtility::retrieveMessage($this->chat));
    }

    public function testRetrieveMessageId()
    {
        $this->assertEquals(227, RsvpBotUtility::retrieveMessageId($this->update));
        $this->assertEquals(227, RsvpBotUtility::retrieveMessageId($this->message));
    }

    public function testGetFromId()
    {
        $this->assertEquals(60875961, RsvpBotUtility::getFromId($this->update));
        $message = RsvpBotUtility::retrieveMessage($this->update);
        $this->assertEquals(60875961, RsvpBotUtility::getFromId($message));
    }

    public function testGetEventDetails()
    {
        $expected = 'Event: Dinner at Storm\'s End' . "\n\n";
        $expected .= 'Miss Clemmie Larson DVM'. "\n";
        $expected .= 'Leonel West IV'. "\n";
        $expected .= 'Casandra Swift'. "\n";
        $expected .= "\n" . 'Number of attendees: 3'. "\n";
        $expected .= 'Click here to attend!'. "\n";
        $expected .= '/attending';

        $this->assertEquals($expected, RsvpBotUtility::getEventDetails(3));
    }

}
