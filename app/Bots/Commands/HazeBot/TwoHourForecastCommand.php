<?php

namespace App\Bots\Commands\HazeBot;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\NeaWeatherForecastAbbrev;

class TwoHourForecastCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "get2hrforecast";

    /**
     * @var string Command Description
     */
    protected $description = "Get 2 Hour Weather Forecast";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.

        $apiUrl   = sprintf("http://api.nea.gov.sg/api/WebAPI/?dataset=2hr_nowcast&keyref=%s", env('NEA_API_KEY'));

        $client   = new \GuzzleHttp\Client();
        $response = $client->request('GET', $apiUrl );

        $data     = $this->convertResponseToArray($response);
        $text     = $this->generateMessageFromData($data);
        $this->replyWithMessage(['text' => $text]);

        // This will update the chat status to typing...

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
        $text .= 'Time of Record: ' . $data['item']['forecastIssue']['@attributes']['date'] . " " . $data['item']['forecastIssue']['@attributes']['time'] . "\n\n";

        // Bedok
        $text .= $data['item']['weatherForecast']['area'][1]['@attributes']['name'] . ' - ' . NeaWeatherForecastAbbrev::interpret($data['item']['weatherForecast']['area'][1]['@attributes']['forecast']) . "\n\n";
        // Boon Lay
        $text .= $data['item']['weatherForecast']['area'][3]['@attributes']['name'] . ' - ' . NeaWeatherForecastAbbrev::interpret($data['item']['weatherForecast']['area'][3]['@attributes']['forecast']) . "\n\n";
        // Changi
        $text .= $data['item']['weatherForecast']['area'][9]['@attributes']['name'] . ' - ' . NeaWeatherForecastAbbrev::interpret($data['item']['weatherForecast']['area'][9]['@attributes']['forecast']) . "\n\n";
        // City
        $text .= $data['item']['weatherForecast']['area'][12]['@attributes']['name'] . ' - ' . NeaWeatherForecastAbbrev::interpret($data['item']['weatherForecast']['area'][12]['@attributes']['forecast']) . "\n\n";
        // Jurong East
        $text .= $data['item']['weatherForecast']['area'][16]['@attributes']['name'] . ' - ' . NeaWeatherForecastAbbrev::interpret($data['item']['weatherForecast']['area'][16]['@attributes']['forecast']) . "\n\n";
        // Yishun
        $text .= $data['item']['weatherForecast']['area'][46]['@attributes']['name'] . ' - ' . NeaWeatherForecastAbbrev::interpret($data['item']['weatherForecast']['area'][46]['@attributes']['forecast']);

        return $text;
    }




}
