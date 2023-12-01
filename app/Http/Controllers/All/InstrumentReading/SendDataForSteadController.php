<?php

namespace App\Http\Controllers\All\InstrumentReading;

use App\Http\Controllers\Controller;
use App\Models\Gardening;
use App\Models\Stead;
use App\Modules\Receipt\Models\DeviceRegisterModel;
use App\Modules\Receipt\Models\ReceiptTypeModels;
use Illuminate\Http\Request;


class SendDataForSteadController extends Controller
{

    protected $errors = false;
    protected $items = [];
    protected $reading = [];


    public function index(Request $request)
    {
        $stead_id = $request->post('stead_id', false);
        $type = $request->post('type', false);
        $values = $request->post('value', []);
        if ($stead_id && $type) {
            $stead = Stead::find($stead_id);
            $receipt_type = ReceiptTypeModels::find($type);
            if ($stead && $receipt_type) {
                $devices = $stead->getMeteringDevice($receipt_type);
                if (count($devices) == 0) {
                    // добавляем прриборы если их нет
                    foreach ($values as $key => $value) {
                        $a = explode('_', $key);
                        if (count($a) == 3 && $a[0] == 'new' && $a[1] == $stead_id && $receipt_type->checkForType((int)$a[2])) {
                            $dev = DeviceRegisterModel::addDevice($stead_id, $a[2]);
                            if ($dev) {
                                $values['dev_' . $stead_id . '_' . $dev->id] = $value;
                            }
                        }
                    }
                    $devices = $stead->getMeteringDevice($receipt_type);
                }
                // разносим показания по приборам
                foreach ($devices as $device) {
                    $this->setDataForDevice($device, $values);
                }
                if ($this->errors) {
                    return ['status' => false, 'error_message' => $this->errors, 'devices' => $this->items];
                } else {
                    return ['status' => true, 'data' => $this->reading];
                }
            }
        }
    }


    public function setDataForDevice($device, $values)
    {
        $val = $this->findValueForDevice($device, $values);
        if ($val) {
            $temp = [
                'id' => 'dev_' . $device->stead_id . '_' . $device->id,
                'type' => $device->MeteringDevice->id,
                'name' => $device->MeteringDevice->name,
                'description' => $device->MeteringDevice->description,
                'last' => 0
            ];
            if ($this->checkLastData($device, $val)) {
                $rez = $device->addReading($val);
                if ($rez) {
                    $last = $rez->getPreviousReadings();
                    $delta = $rez->value - $last;
                    $temp = [
                        'id' => $rez->id,
                        'value' => $rez->value,
                        'delta' => $delta,
                        'unit_name' => $rez->getUnit(),
                        'price' => $rez->getPrice(),
                        'name' => $rez->deviceTypeName(),
                        'last' => $last,
                        'sum' => $rez->getPrice() * $delta
                    ];
                    $this->reading[] = $temp;
                } else {
                    $this->errors = 'Ошибка сохранений показаний';
                    return false;
                }
            } else {
                $temp['last'] = $val;
            }
            $this->items[] = $temp;
        }
        return true;
    }

    public function findValueForDevice($device, $values)
    {
        $key_name = 'dev_' . $device->stead_id . '_' . $device->id;
        if (isset($values[$key_name])) {
            return (int)$values[$key_name];
        }
        return false;
    }

    /**
     * сравнить показания с предыдущими
     *
     * @param $device
     * @param $value
     * @return bool
     */
    public function checkLastData($device, $value)
    {
        $last_data = $device->getLastReadingValue();
        if ($last_data <= $value) {
            return true;
        }
        $this->errors = 'Показания не могуть быть меньше предыдущих!';
//        $key_name = 'dev_'. $device->stead_id. '_' . $device->id;
//        $this->error_items[] = $key_name = 'dev_'. $device->stead_id. '_' . $device->id;
        return false;
    }


}
