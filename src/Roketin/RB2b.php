<?php

namespace Roketin;

class RB2b extends Roketin
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
    public function join($name, $email, $phone, $title, $message, $bcc = null, $requestb2b = true)
    {
        return $this->callAPI("message", compact('name', 'email', 'phone', 'title', 'message', 'bcc', 'requestb2b'), "POST");
    }
}
