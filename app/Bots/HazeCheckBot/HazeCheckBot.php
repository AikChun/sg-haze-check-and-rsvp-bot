<?php

namespace App\Bots\HazeCheckBot;

use Telegram;
use App\MessageUpdate;

class HazeCheckBot
{

    public function filterNewUpdatesSinceLastUpdateId($updates, $lastUpdateId)
    {
        return array_filter($updates, function($update) use($lastUpdateId) {
            return $update['update_id'] > $lastUpdateId;
        });
    }

    public function getMaxUpdateId($updates)
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

}
