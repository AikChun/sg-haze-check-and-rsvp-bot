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
    use DatabaseTransactions;

    protected $update;
    protected $message;

    public function setUp()
    {
        parent::setUp();

        $this->message = new Message([
            'message_id' => 227,
        ]);

        //$this->update = new Update([
        //    'update_id' => 446324986,
        //    'message'   => $this->message,

        //]);

        $this->update = factory(Telegram\Bot\Objects\Update::class)->make();

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
        $this->assertTrue(null == RsvpBotUtility::retrieveMessage($this->update->getMessage()->getChat()));
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
        $expected =  'Event: '. "\n";
        $expected .= 'Breakfast at Winterfell' . "\n\n";
        $expected .= '1000test_user'. "\n";
        $expected .= '1001test_user'. "\n";
        $expected .= '1002test_user'. "\n";
        $expected .= "\n" . 'Number of attendees: 3'. "\n";
        $expected .= 'Click here to attend!'. "\n";
        $expected .= '/attending';

        $this->assertEquals($expected, RsvpBotUtility::getEventDetails(1));
    }

    public function testRetrieveFromUser()
    {
        $user = RsvpBotUtility::retrieveFromUser($this->update);
        $this->assertTrue($user instanceof User);
        $this->assertEquals('EggyMcEggface', $user->getUsername());
        $this->assertEquals(60875961, $user->getId());
    }

}
