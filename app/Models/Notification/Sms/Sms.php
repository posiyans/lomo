<?php

namespace App\Models\Notification\Sms;

use App\Models\MyModel;

/**
 * App\Models\Notification\Sms\Sms
 *
 * @property int $id
 * @property int|null $user_id кому отправленно
 * @property int|null $sender_id кто отправлял
 * @property string $phone номер телефона
 * @property string $text текст смс
 * @property string $type тип смс
 * @property string|null $gateway шлюз отправки
 * @property string|null $price цена смс
 * @property string|null $sms_id идентификатор смс на шлюзе
 * @property string|null $status статус смс
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|Sms newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sms newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sms query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sms whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sms whereGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sms whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sms wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sms wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sms whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sms whereSmsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sms whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sms whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sms whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sms whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sms whereUserId($value)
 * @mixin \Eloquent
 */
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
                if ($model->logAndSave('Отправка СМС на ' . $phone . ' код: ' . $code) && $model->status == 'OK') {
                    return true;
                }
            }
        }
        return false;
    }

    public static function getGateway(string $name)
    {
        $className = __DIR__ . '/SmsGateway/' . $name . '/SmsController.php';
        if (file_exists($className)) {
            require_once($className);
            $class = '\\' . $name . '\SmsController';
            return $class;
        } else {
            return false;
        }
    }


    public static function validCode($phone, $code)
    {
        $valid = self::where('phone', $phone)->where('text', $code)->where('type', 'code')->first();
        if ($valid) {
            $valid->type = 'old_code';
            $valid->save();
            return true;
        }
        return false;
    }


}
