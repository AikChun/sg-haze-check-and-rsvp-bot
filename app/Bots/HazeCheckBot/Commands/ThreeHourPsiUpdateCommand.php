<?php

namespace App\Bots\HazeCheckBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class ThreeHourPsiUpdateCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "get3hrpsi";

    /**
     * @var string Command Description
     */
    protected $description = "Get 3 Hour PSI Updates";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.

        $apiUrl = sprintf("http://api.nea.gov.sg/api/WebAPI/?dataset=psi_update&keyref=%s", env('NEA_API_KEY'));

        $client = new \GuzzleHttp\Client();
        $response =  $client->request('GET', $apiUrl );
        $data = $this->convertResponseToArray($response);
        $text = $this->generateMessageFromData($data);

        $this->replyWithMessage(['text' => $text]);

        // This will update the chat status to typing...
        $this->replyWithChatAction(['action' => Actions::TYPING]);

    }

    private function convertResponseToArray($response)
    {
        $xmlData  = simplexml_load_string($response->getBody()->getContents());
        $jsonData = json_encode($xmlData);
        return json_decode($jsonData,TRUE);
    }

    public function generateMessageFromData($data)
    {
        $text = $data['title'] . "\n\n";
        $text .= "Source: " . $data['source'] . "\n\n";
        $text .= "Time of Record: " . date('D j-n-Y H:i', strtotime($data['item']['region'][0]['record']['@attributes']['timestamp'])) . "\n\n";
        $text .= "Region: " . "\n\n";
        $text .= "North - " . $data['item']['region'][0]['record']['reading'][1]['@attributes']['value'] . "\n\n";
        $text .= "Central - " . $data['item']['region'][2]['record']['reading'][1]['@attributes']['value'] . "\n\n";
        $text .= "East - " . $data['item']['region'][3]['record']['reading'][1]['@attributes']['value'] . "\n\n";
        $text .= "West - " . $data['item']['region'][4]['record']['reading'][1]['@attributes']['value'] . "\n\n";

        return $text;
    }




}
