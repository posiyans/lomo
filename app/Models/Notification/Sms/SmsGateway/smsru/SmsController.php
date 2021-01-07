<?php

namespace smsru;

use App\Models\Notification\Sms\SmsInterface;
use Illuminate\Routing\Controller as BaseController;

require_once 'sms.ru.php';


class SmsController extends BaseController implements SmsInterface {

    protected static $apikey = '';
    protected $object;

    function __construct()
    {
        static::$apikey=env('SMS_API_KEY');
    }

    public static function sendSms($phone, $text)
    {
        // TODO: Implement sendSms() method.
        //$apikey=env('SMS_API_KEY');
        $object = new self();
        var_dump(self::$apikey);
        $smsru = new \SMSRU(self::$apikey);
        $data = new \stdClass();
        $data->to = $phone;
        $data->text = $text; // Текст сообщения
        $data->partner_id = '2316'; // Текст сообщения
        $object->object = $smsru->send_one($data); // Отправка сообщения и возврат данных в переменную.
        return $object;
    }

    public function getStatus()
    {
        // TODO: Implement getStatus() method.
        return $this->object->status;
    }

    public function getSmsId()
    {
        // TODO: Implement getSmsId() method.
        return $this->object->sms_id;
    }


    public function getPrice()
    {
        // TODO: Implement getPrice() method.
        return $this->object->cost;
    }
}
