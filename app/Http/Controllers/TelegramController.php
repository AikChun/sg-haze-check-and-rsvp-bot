<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Http\Requests;
use Telegram;
use App\MessageUpdate;
use App\Bots\HazeCheckBot\HazeCheckBot;

class TelegramController extends Controller
{
    public function setWebhook()
    {
       $response = Telegram::setWebhook(['url' => 'https://pickira.com/hazebot/'. env('TELEGRAM_BOT_TOKEN'). '/webhook']);
       return $response;
    }

    public function webhook()
    {
        $updates         = file_get_contents('php://input');
        $updatesInObject = json_decode($updates, true);
        $updates         = $this->rebuildBrokenJson($updatesInObject);
        Log::info(print_r($updates, true));
        $lastUpdate      = MessageUpdate::orderBy('id', 'desc')->first();
        $lastUpdateId    = 0;

        if(is_object($lastUpdate)) {
            $lastUpdateId = $lastUpdate->update_id;
        }

        // getNewUpdatesSinceLastUpdateId
        $hazeCheckBot = new HazeCheckBot;

        $newUpdates = $hazeCheckBot->filterNewUpdatesSinceLastUpdateId($updates, $lastUpdateId);

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

        return 'OK';
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

        Log::info(var_dump($update['message']));
        $command = strtolower($update['message']['text']);
        $dataset = $this->getDatasetToNea($command);

        if($dataset === false) {
            return false;
        }


        $apiUrl = sprintf("http://api.nea.gov.sg/api/WebAPI/?dataset=%s&keyref=%s", $dataset, env('NEA_API_KEY'));

        $client = new \GuzzleHttp\Client();
        $response =  $client->request('GET', $apiUrl );
        $xmlData = simplexml_load_string($response->getBody()->getContents());
        $jsonData = json_encode($xmlData);
        $dataArray = json_decode($jsonData,TRUE);

        $message['text'] = $this->prepareDataIntoText($dataArray, $command);

        $response = Telegram::sendMessage($message);

    }

    protected function getDatasetToNea($type)
    {
        $dataset = "";

        switch($type) {
            case "/get3hrpsi":
            case "/get3hrpsi@sghazecheckbot":
                $dataset="psi_update";
                break;
            case "/getforecast@sghazecheckbot":
            case "/getforecast":
                $dataset="24hrs_forecast";
                break;

            default:
                return false;

        }

        return $dataset;

    }

    protected function prepareDataIntoText($data, $command)
    {
        $pieces = explode("@", $command);
        $text = "";
        if($pieces[0] == "/getforecast") {
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
        }

        if($pieces[0] == "/get3hrspsi") {
            $text = $data['title'] . "\n\n";
            $text .= "Time of Record: " . date('D j-n-Y H:i', strtotime($data['item']['region'][0]['record']['-timestamp'])) . "\n\n";
            $text .= "Region: " . "\n\n";
            $text .= "North - " . $data['item']['region'][0]['record']['reading'][1]['value'] . "\n\n";
            $text .= "Central - " . $data['item']['region'][2]['record']['reading'][1]['value'] . "\n\n";
            $text .= "East - " . $data['item']['region'][3]['record']['reading'][1]['value'] . "\n\n";
            $text .= "West - " . $data['item']['region'][4]['record']['reading'][1]['value'] . "\n\n";
        }
        return $text;
    }

    protected function rebuildBrokenJson($updates)
    {
        $newArray = [];
        $temp = [];
        foreach($updates as $update) {
            $temp[] = $update;
        }
        for($i=0;$i<count($temp);$i++) {
            $newEntry = [
                'update_id' => $temp[$i],
                'message' => $temp[$i+1],
            ];

            $newArray[] = $newEntry;
            $i++;
        }
        return $newArray;
    }



}
