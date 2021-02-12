<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\RegisterOfCharges;

use App\Http\Resources\Admin\Bookkeeping\AdminInvoiceResource;
use App\Http\Resources\Admin\Bookkeeping\AdminPaymentResource;
use App\Models\Billing\BillingInvoice;
use App\Models\Billing\BillingPayment;
use App\Models\Billing\BillingReestr;
use App\Models\Receipt\InstrumentReadings;
use App\Models\Receipt\MeteringDevice;
use App\Models\Receipt\ReceiptType;
use App\Models\Stead;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;

class AddRegisterOfChargesController extends Controller
{

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }


    /**
     * сделать переодическое начисление
     *
     * @param $type
     * @param $receipt
     * @param false $date
     * @param false $title
     * @return bool[]|false[]
     */
    public static function addRegister($type, $receipt, $date = false, $title = false)
    {
        if ($type && is_array($receipt)) {
            $receiptModel = ReceiptType::find($type);
            if ($receiptModel) {
                $rates = MeteringDevice::whereIn('id', $receipt)->where('type_id', $receiptModel->id)->get();
                $reestr = new BillingReestr();
                $reestr->title = $title;
                $reestr->type = $type;
                $reestr->options = $rates;
                DB::beginTransaction();
                if ($reestr->logAndSave('Добавлено начисление')) {
                     $result = false;
                    if ($receiptModel->depends == 1) {
                        $result = (new self)->setInvoiceForStead($title, $rates, $type, $reestr->id);
                    } else if ($receiptModel->depends == 2) {
                        $result = (new self)->setInvoiceForDevice($title, $rates, $type, $reestr->id);
                    }
                    $reestr->options = $rates;
                    if ($result && $reestr->save()) {
                        DB::commit();
                        return ['status' => true];
                    }
                }
                DB::rollBack();
            }
        }
        return ['status' => false];
    }


    /**
     * сделать начисление зависищае от площади учатска
     *
     * @param $title
     * @param $devices
     * @param $type
     * @param $reestr_id
     * @return bool
     */
    public  function setInvoiceForStead($title, $devices, $type, $reestr_id)
    {
        $steads = Stead::all();
        $s = [];
        $status = true;
        foreach ($steads as $stead) {
            $description  = '';
            $price = 0;
            foreach ($devices as $device) {
                $device->rateNow();
                $price += $device->rate['ratio_a'] * ($stead->size / 100);
                $price += $device->rate['ratio_b'];
                if ($device->rate['ratio_a'] > 0 || $device->rate['ratio_b'] > 0) {
                    $description.=$device->name .': ';
                    if ($device->rate['ratio_a'] > 0) {
                       $description .= ($stead->size / 100) .' * '. $device->rate['description'];
                       $description .= ' = ' . $device->rate['ratio_a'] * ($stead->size / 100) . ' руб';
                    }
                    if ($device->rate['ratio_b'] > 0) {
                        $description .= $device->rate['description'];
                    }
                    $description .= ';@';
                }
            }
            $description .= 'Итого: '. $price . ' руб.';
            if (!BillingInvoice::createInvoiceForStead($stead->id, $price, $title, $type, $date = false, $reestr_id, $description)) {
                $status = false;
            }
        }
        return $status;
    }


    /**
     * сделать начисление по приборам учета в зависимости от показаний (электричесто, вода)
     *
     * @param $title
     * @param $devices
     * @param $type
     * @param $reestr_id
     * @return bool
     */
    public function setInvoiceForDevice($title, $devices, $type, $reestr_id)
    {
        $steads = Stead::all();
        $status = true;
        foreach ($steads as $stead) {
            $description = '';
            $summa = 0;
            foreach ($devices as $device) {
                $device->rateNow();
                $device_list = $device->getDeviceForStead($stead->id);
                if (count($device_list) > 0) {
                    foreach ($device_list as $register) {
                        $last_readings = $register->getLastReading();
                        $t = $this->getPriceAndDescrription($last_readings);
                        $summa += $t['summa'];
                        $description .= $t['description'];
                    }
                }
            }
            $description .= 'Итого: ' . $summa . ' руб.';
            if ($summa > 0) {
                if (!BillingInvoice::createInvoiceForStead($stead->id, $summa, $title, $type, $date = false, $reestr_id, $description)) {
                    $status = false;
                }
            }
        }
            return $status;
    }


    /**
     * получить сумму и описание по итерациям по показаниям до показния на которой у же выставлен счет
     *
     * @param $last_readings от какого показания выставить счета
     * @return array
     */
    public function getPriceAndDescrription($last_readings)
    {
        $summa = 0;
        $description = '';
        while ($last_readings->invoice_id === null) {
            $old = $last_readings->getPreviousReadings();
            $price = $last_readings->getPrice();
            $delta = $last_readings->value - $old;
            if ($price > 0 && $delta > 0) {
                $summa += $delta * $price;
                $t = $last_readings->deviceTypeName();
                $description .= $t[1] . ' ' . $delta . '(' . $last_readings->value . '-' . $old . ') * ' . $price . ' руб@';
            }
            $last_readings = $last_readings->getPreviousReadingsModel();;
            if (get_class($last_readings) == 'App\\Models\\Receipt\\DeviceRegisterModel') {
                $last_readings->invoice_id = 1;
            }
        }
        return ['summa' => $summa, 'description' => $description];
    }


}
