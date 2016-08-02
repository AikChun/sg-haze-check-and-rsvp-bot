<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Http\Requests;
use Telegram;
use App\MessageUpdate;

class TelegramController extends Controller
{
    public function setWebhook()
    {
       $response = Telegram::setWebhook(['url' => 'https://pickira.com/hazebot/'. env('TELEGRAM_BOT_TOKEN'). '/webhook']);
       return $response;
    }

    public function webhook()
    {
        $updates = file_get_contents('php://input');
        $updatesInObject = json_decode($updates);
        $updates = $this->rebuildBrokenJson($updatesInObject);

        $lastUpdate = MessageUpdate::orderBy('id', 'desc')->first();
        $lastUpdateId = 0;

        if(is_object($lastUpdate)) {
            $lastUpdateId = $lastUpdate->update_id;
        }

        // getNewUpdatesSinceLastUpdateId
        $newUpdates = $this->filterNewUpdatesSinceLastUpdateId($updates, $lastUpdateId);

        if(count($newUpdates) < 1 ) {
            return count($newUpdates);
        }

        array_walk($newUpdates,  function($update) {
            $this->handleEachUpdate($update);
        });

        $maxUpdateId = $this->getMaxUpdateId($newUpdates);
        $messageUpdate = new MessageUpdate;
        $messageUpdate->update_id = $maxUpdateId;
        $messageUpdate->save();

        return $response;
    }

    public function removeWebhook()
    {
        $response = Telegram::removeWebhook();
    }

    public function replyToMessages()
    {
        $updates = Telegram::getUpdates();
        // find last updateId

    }

    protected function filterNewUpdatesSinceLastUpdateId($updates, $lastUpdateId)
    {
        return array_filter($updates, function($update) use($lastUpdateId) {
            return $update['update_id'] > $lastUpdateId;
        });
    }

    protected function getMaxUpdateId($updates)
    {
        return max(array_map(function($update){
            return $update['update_id'];
        }, $updates));

    }

    protected function handleEachUpdate($update)
    {
        $message = [
            'chat_id' => $update['message']['chat']['id'],
        ];

        $data = $this->getDataFromNea($update['message']['text']);

        $message['text'] = $this->prepareDataIntoText($data);

        $response = Telegram::sendMessage($message);

    }

    protected function getDataFromNea($type)
    {
        $client = new \GuzzleHttp\Client();
        $dataset = "";

        switch($type) {
            case "/todayforecast":
                $dataset="24hrs_forecast";
                break;

             default:
                 $text = 'Sorry this command is not within my actions';

        }
        $apiUrl = 'http://api.nea.gov.sg/api/WebAPI/?dataset=';
        $apiUrl .= $dataset . '&keyref=' . env('NEA_API_KEY');
        $client = new \GuzzleHttp\Client();
        $response =  $client->request('GET', $apiUrl );
        $xml = simplexml_load_string($response->getBody()->getContents());
        $json = json_encode($xml);
        return json_decode($json,TRUE);

    }

    protected function prepareDataIntoText($data)
    {
        //return var_dump($data['main']);
        $text = $data['title'] . "\n\n";
        $text .= $data['main']['title'] . "\n\n";
        //$text .= $data['main']['forecastIssue']['@attributes']['date'] . "\n\n";
        //$text .= $data['main']['forecastIssue']['@attributes']['time'] . "\n\n";
        $text .= $data['main']['validTime'] . "\n\n";
        $text .= "Temperature: " . "\n\n";
        $text .= 'High: ' . $data['main']['temperature']['@attributes']['high'] . "\n\n";
        $text .= 'Low: ' . $data['main']['temperature']['@attributes']['low'] . "\n\n";
        $text .= $data['main']['forecast'] . "\n\n";
        return $text;
    }

    protected function rebuildBrokenJson($updates)
    {
        $newArray = [];
        foreach($updates as $update) {
            $newEntry = [];
            if(is_numeric($update)) {
                Log::info($update);
                $newEntry['update_id'] = $update;
            }
            if(is_array($update)) {
                $newEntry['message'] = $update;
            }
            $newArray[] = $newEntry;
        }
        return $newArray;
    }



}
