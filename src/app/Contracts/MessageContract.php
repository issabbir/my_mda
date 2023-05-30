<?php

namespace App\Contracts;


interface MessageContract
{
    /**
     * @param $to
     * @param $content
     * @return int|null
     */
    public function send($to, $content);
    public function queueMessages();
    public function update($request,$id);
    public function sendMail($obj,$mail);
    public function sendSms();
    public function sendMweSms();
}
