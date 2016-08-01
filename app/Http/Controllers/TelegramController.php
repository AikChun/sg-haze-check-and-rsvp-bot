<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Telegram;

class TelegramController extends Controller
{

    public function getUpdates()
    {
        $updates = Telegram::getUpdates();
        return $updates;
    }


}
