<?php

namespace App\Bots\Commands\HazeBot;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class TwentyFourHourForecastCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "getforecast";

    /**
     * @var string Command Description
     */
    protected $description = "Get 24 Hour Weather Forecast";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.

        $apiUrl   = sprintf("http://api.nea.gov.sg/api/WebAPI/?dataset=24hrs_forecast&keyref=%s", env('NEA_API_KEY'));

        $client   = new \GuzzleHttp\Client();
        $response = $client->request('GET', $apiUrl );

        $data     = $this->convertResponseToArray($response);
        $text     = $this->generateMessageFromData($data);
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

    private function generateMessageFromData($data)
    {
        $text = $data['title'] . "\n\n";
        $text .= 'Source: ' . $data['source'] . "\n\n";
        $text .= $data['main']['title'] . "\n\n";
        $text .= $data['main']['validTime'] . "\n\n";
        $text .= "Temperature: " . "\n\n";
        $text .= 'High: ' . $data['main']['temperature']['@attributes']['high'] . "\n\n";
        $text .= 'Low: ' . $data['main']['temperature']['@attributes']['low'] . "\n\n";
        $text .= $data['main']['forecast'] . "\n\n";

        return $text;
    }




}
