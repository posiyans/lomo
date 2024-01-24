<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\InstrumentReadings;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Models\BillingInvoice;
use App\Modules\MeteringDevice\Models\InstrumentReadingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddGroupInvoiceController extends Controller
{


    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    /**
     * Добавить счета  группк показаний
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $action = $request->post('action', false);
        $readings = $request->post('readings', false);
        if ($action && $readings && is_array($readings)) {
            $items = InstrumentReadingModel::whereIn('id', $readings)->whereNull('invoice_id')->orderBy('created_at', 'asc')->get();
        }

        if ($action == 1) {
            DB::beginTransaction();
            $status = true;
            foreach ($items as $item) {
                if (!$this->setInvoiceForReading($item)) {
                    $status = false;
                }
            }
            if ($status) {
                DB::commit();
                return ['status' => true];
            } else {
                DB::rollBack();
            }
        }
        if ($action == 2) {
            if ($this->groupForDateAndSetInvoice($items)) {
                return ['status' => true];
            }
        }

        return ['status' => false];
    }


    public function setInvoiceForReading(InstrumentReadingModel $reading)
    {
        $price = $reading->getPrice();
        $old = $reading->getPreviousReadings();
        $stead_id = $reading->deviceRegister->stead_id;
        $receipt_type = $reading->deviceRegister->meteringDevice->type_id;
        $delta = $reading->value - $old;
        if ($price > 0 && $delta > 0) {
            $summa = $delta * $price;
            $title = $reading->deviceTypeName();
            $title = $title[1] . ' ' . $delta . '(' . $reading->value . '-' . $old . ')';
            $invoice = BillingInvoice::createInvoiceForStead($stead_id, $summa, $title, $receipt_type, $reading->created_at);
            if ($invoice) {
                $reading->invoice_id = $invoice->id;
                $reading->save();
                return true;
            }
        }
        return false;
    }

    /**
     *
     * @param $items
     * @return bool
     */
    public function groupForDateAndSetInvoice($items)
    {
        $groups = $this->groupForDate($items);
        $status = true;
        DB::beginTransaction();
        foreach ($groups as $key => $group) {
            $date = date('Y-m-d', strtotime('+1 month', strtotime($key . '-1')));
            if (!$this->setInvoiceForGroupReading($group, $date)) {
                $status = false;
            }
        }
        if ($status) {
            DB::commit();
            return true;
        } else {
            DB::rollBack();
        }
        return false;
    }

    /**
     * сгрупировать показания по месяцам
     *
     * @param $items
     */
    public function groupForDate($items)
    {
        $groups = [];
        foreach ($items as $item) {
            $date = date('Y-m', strtotime($item->created_at));
            if (isset($groups[$date])) {
                $groups[$date][] = $item;
            } else {
                $groups[$date] = [$item];
            }
        }
        return $groups;
    }


    /**
     * выставить счет группе показний
     *
     * @param $items
     * @param false $date
     * @return bool
     */
    public function setInvoiceForGroupReading($items, $date = false)
    {
        $price = 0;
        $types = [];
        $stead_id = true;
        foreach ($items as $reading) {
            if ($stead_id === true) {
                $stead_id = $reading->deviceRegister->stead_id;
            } else {
                if (is_numeric($stead_id) && $stead_id != $reading->deviceRegister->stead_id) {
                    $stead_id = false;
                }
            }
            $price = $reading->getPrice();
            $old = $reading->getPreviousReadings();
            $delta = $reading->value - $old;
            $receipt_type = $reading->deviceRegister->meteringDevice->type_id;
            if ($price > 0 && $delta > 0) {
                $summa = $delta * $price;
                $title = $reading->deviceTypeName();
                $title = $title[1] . ' ' . $delta . '(' . $reading->value . '-' . $old . ')';
                if (isset($types[$receipt_type])) {
                    $types[$receipt_type][] = ['summa' => $summa, 'title' => $title, 'id' => $reading->id];
                } else {
                    $types[$receipt_type] = [['summa' => $summa, 'title' => $title, 'id' => $reading->id]];
                }
            }
        }
        if ($stead_id) {
            if (!$date) {
                $date = date('Y-m-d');
            }
            foreach ($types as $type => $item) {
                $summa = 0;
                $title = '';
                $id = [];
                foreach ($item as $i) {
                    $summa += $i['summa'];
                    $title .= $i['title'] . ' ';
                    $id[] = $i['id'];
                }
                $invoice = BillingInvoice::createInvoiceForStead($stead_id, $summa, $title, $type, $date);
                if ($invoice) {
                    InstrumentReadingModel::whereIn('id', $id)->update(['invoice_id' => $invoice->id]);
                    return true;
                }
            }
        }
        return false;
    }

}
