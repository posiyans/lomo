<?php

namespace App\Models\Notification\Sms;

interface SmsInterface
{
    public static function sendSms($phone, $text);
    public function getStatus();
    public function getSmsId();
    public function getPrice();
}
