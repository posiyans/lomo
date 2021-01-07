<?php

namespace App\Models\Notification\Sms;

use App\Http\Controllers\Admin\Bookkeeping\Billing\PaymentController;
use App\MyModel;
use Illuminate\Database\Eloquent\Model;

class Sms extends MyModel
{
    //
    protected $table = 'notification_sms';


    public static function sendCode($phone, int $lenght = 4)
    {
        $gateway = env('SMS_GATEWAY', false);
        if ($gateway) {
            $class = self::getGateway($gateway);
            if ($class) {
                $min = pow(10, $lenght - 1);
                $max = pow(10, $lenght) - 1;
                $code = rand($min, $max);
                $sms = $class::sendSms($phone, $code);
                $model = new self();
                $model->phone = $phone;
                $model->type = 'code';
                $model->text = $code;
                $model->gateway = get_class($sms);
                $model->status = $sms->getStatus();
                $model->sms_id = $sms->getSmsId();
                $model->price = $sms->getPrice();
                if ($model->logAndSave('Отправка СМС на '. $phone . ' код: ' . $code) && $model->status == 'ok') {
                    return true;
                }
            }
        }
        return false;
    }

    public static function getGateway(string $name)
    {
        $className = __DIR__.'/SmsGateway/'.$name.'/SmsController.php';
        if (file_exists($className)) {
            require_once($className);
            $class = '\\'.$name.'\SmsController';
            return $class;
        } else {
            return false;
        }
    }


    public static function validCode($phone, $code)
    {
        var_dump($phone);
        var_dump($code);
        $valid = self::where('phone', $phone)->where('text', $code)->where('type', 'code')->first();
        if($valid) {
            $valid->type = 'old_code';
            $valid->save();
            return true;
        }
        return false;
    }


}
