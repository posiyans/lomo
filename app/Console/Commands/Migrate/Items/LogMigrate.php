<?php

namespace App\Console\Commands\Migrate\Items;

use App\Modules\Article\Models\ArticleModel;
use App\Modules\Billing\Models\BillingInvoiceModel;
use App\Modules\Billing\Models\BillingPaymentModel;
use App\Modules\Billing\Models\BillingReestrModel;
use App\Modules\Log\Models\LogModel as LogModelOriginal;
use App\Modules\Owner\Models\OwnerUserModel;
use App\Modules\Owner\Models\OwnerUserSteadModel;
use App\Modules\Owner\Models\OwnerUserValueModel;
use App\Modules\Rate\Models\RateModel;
use App\Modules\Receipt\Models\DeviceRegisterModel;
use App\Modules\Receipt\Models\InstrumentReadingModel;
use App\Modules\Receipt\Models\ReceiptTypeModels;
use App\Modules\Stead\Models\SteadModel;
use App\Modules\User\Models\UserModel;
use Illuminate\Support\Facades\DB;

class LogMigrate
{

    public static function run()
    {
        echo 'Конвертируем логи' . PHP_EOL;
        $atr = [
            'id',
            'user_id',
            'commentable_id',
            'commentable_type',
            'type',
            'action',
            'user_agent',
            'description',
            'value',
            'created_at',
            'updated_at',
        ];

        $soc = DB::connection('mysql_old')->table('logs')->get();
        foreach ($soc as $item) {
            $newItem = new LogModel();
            foreach ($atr as $key) {
                $newItem->$key = $item->$key;
            }
            $class = $item->commentable_type;
            $class = str_replace('App\Models\Stead', SteadModel::class, $class);
            $class = str_replace('App\User', UserModel::class, $class);
            $class = str_replace('App\Models\UserModel', UserModel::class, $class);
            $class = str_replace('App\Models\Owner\OwnerUserModel', OwnerUserModel::class, $class);
            $class = str_replace('App\Models\Owner\OwnerUserSteadModel', OwnerUserSteadModel::class, $class);
            $class = str_replace('App\Models\Owner\OwnerUserValueModel', OwnerUserValueModel::class, $class);
            $class = str_replace('App\Models\Article\ArticleModel', ArticleModel::class, $class);


            $class = str_replace('App\Models\Billing\BillingInvoice', BillingInvoiceModel::class, $class);
            $class = str_replace('App\Models\Billing\BillingPayment', BillingPaymentModel::class, $class);
            $class = str_replace('App\Models\Billing\BillingReestr', BillingReestrModel::class, $class);

            $class = str_replace('App\Models\Receipt\ReceiptType', ReceiptTypeModels::class, $class);
            $class = str_replace('App\Models\Receipt\Rate', RateModel::class, $class);
            $class = str_replace('App\Models\Receipt\InstrumentReadings', InstrumentReadingModel::class, $class);
            $class = str_replace('App\Models\Receipt\DeviceRegisterModel', DeviceRegisterModel::class, $class);
//            $class = str_replace('', '', $class);

            $newItem->commentable_type = $class;
            $newItem->save();
        }
    }

}

class LogModel extends LogModelOriginal
{
    public $timestamps = false;
}