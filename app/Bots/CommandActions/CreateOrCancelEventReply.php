<?php

class CreateOrCancelEventReply extends AReply
{
    public function process($update)
    {
        $keyboard = [
            'Create',
            'Cancel'
        ];

        $this->messageParameters->setChatId($update->getMessage()->getChat()->getId());
        $this->messageParameters->setText("There's already an existing event.\n Continue by choosing to *Create* or *Cancel*.");
        $this->messageParameters->setParseMode('Markdown');

        $replyMarkup = [
            'keyboard'         => $keyboard,
            'resizekeyboard'   => true,
            'onetime_keyboard' => true,
            'selective'        => true,
            'force_reply'      => true,
        ];

        $this->messageParameters->setReplyMarkup($replyMarkup);

    }
}
