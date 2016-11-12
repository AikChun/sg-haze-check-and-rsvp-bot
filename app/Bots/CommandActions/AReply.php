<?php
namespace App\Bots\CommandActions;

use MessageParameters;

abstract class AReply
{
    public function __construct()
    {
        $this->messageParameters = new MessageParameters;
    }

    abstract public function process($update);

    public function getReplyParams()
    {
        return $this->messageParameters->toArray();
    }
}
