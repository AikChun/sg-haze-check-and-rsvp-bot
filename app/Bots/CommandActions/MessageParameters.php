<?php
class MessageParameters
{
    protected $chatId                = null;
    protected $text                  = '';
    protected $parseMode             = null;
    protected $disableWebPagePreview = false;
    protected $disableNotification   = false;
    protected $replyToMessageId      = null;
    protected $replyMarkup           = null;

    public function __construct()
    {

    }

    public function toArray()
    {
        return [
            'chat_id'                  => $this->chatId,
            'text'                     => $this->text,
            'parse_mode'                => $this->parseMode,
            'disable_web_page_preview' => $this->disableWebPagePreview,
            'disable_notification'     => $this->disableNotification,
            'reply_to_message_id'      => $this->replyToMessageId,
            'reply_markup'             => $this->replyMarkup,
        ];
    }

    protected function setChatId($chatId)
    {
        $this->chatId = $chatId;
    }

    protected function setText($text)
    {
        $this->text = $text;
    }

    protected function setParseMode($parseMode)
    {
        $this->parseMode = $parseMode;
    }

    protected function setDisableWebPagePreview($disableWebPagePreview)
    {
        $this->disableWebPagePreview = $disableWebPagePreview;
    }

    protected function setDisableNotification($disableNotification)
    {
        $this->disableNotification = $disableNotification;
    }

    protected function setReplyToMessageId($replyToMessageId)
    {
        $this->replyToMessageId = $replyToMessageId;
    }

    protected function setReplyMarkup($replyMarkup)
    {
        $this->replyMarkup = $replyMarkup;
    }
}
