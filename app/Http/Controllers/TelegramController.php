<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Irazasyed\Telegram\Telegram;

class TelegramController extends Controller
{
    protected $telegram;

    public function __construct(Telegram $telegram)
    {
        $this->telegram = $telegram;
    }


    public function getUpdates()
    {
        $updates = $this->telegram->getUpdates();
        dd($updates);
    }


}
