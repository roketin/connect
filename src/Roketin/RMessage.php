<?php

namespace Roketin;

class RMessage extends Roketin
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $sender_name
     * @param $sender_email
     * @param $sender_phone
     * @param $message_title
     * @param $message_body
     * @param $bcc
     * @return mixed
     */
    public function send($name, $email, $phone, $title, $message, $bcc = null)
    {
        return $this->callAPI("message", compact('name', 'email', 'phone', 'title', 'message', 'bcc'), "POST");
    }
}
