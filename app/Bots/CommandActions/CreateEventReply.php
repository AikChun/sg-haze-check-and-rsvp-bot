<?php
namespace App\Bots\CommandActions;

use MessageParameters;
use AReply;

class CreateEventReply extends AReply;
{

    public function process($update)
    {
        $this->messageParameters->setChatId($update->getMessage()->getChat()->getId());

        $this->messageParameters->setText('What is your event?');

        return $this->messageParameters->toArray();
    }

}
