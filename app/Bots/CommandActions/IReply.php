<?php

interface IReply
{
    // construct a reply array
    // [
    //     chat_id:	Mixed Required  Unique identifier for the target chat or username of the target channel (in the format @channelusername)
    //     text:	string Required Text of the message to be sent
    //     parse_mode:	string Optional Send Markdown or HTML, if you want Telegram apps to show bold, italic, fixed-width text or inline URLs in your bot's message.
    //     disable_web_page_preview:	boolean Optional Disables link previews for links in this message
    //     disable_notification:	boolean Optional Sends the message silently. iOS users will not receive a notification, Android users will receive a notification with no sound. Other apps coming soon.
    //     reply_to_message_id:	integerOptional If the message is a reply, ID of the original message
    //     reply_markup:	objectOptional Additional interface options. A JSON-serialized object for a custom reply keyboard, instructions to hide keyboard or to force a reply from the user.
    // ]
    public function process();
}
