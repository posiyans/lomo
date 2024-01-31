<?php

namespace App\Modules\MeteringDevice\Repositories;

use App\Modules\MeteringDevice\Models\InstrumentReadingModel;

class InstrumentReadingRepository
{

    private $query;

    public function __construct()
    {
        $this->query = InstrumentReadingModel::query();
    }

    /**
     * отбор по участку
     *
     * @param $steadId
     * @return $this
     */
    public function for_stead($steadId)
    {
        if ($steadId) {
            if (is_array($steadId)) {
                $this->query->whereHas('metering_device', function ($query) use ($steadId) {
                    $query->whereIn('stead_id', $steadId);
                });
            } else {
                $this->query->whereHas('metering_device', function ($query) use ($steadId) {
                    $query->where('stead_id', $steadId);
                });
            }
        }
        return $this;
    }

    /**
     * отбор по прибоу учета
     *
     * @param $deviceId
     * @return $this
     */
    public function for_device($deviceId): InstrumentReadingRepository
    {
        if ($deviceId) {
            if (is_array($deviceId)) {
                $this->query->whereHas('metering_device', function ($query) use ($deviceId) {
                    $query->whereIn('id', $deviceId);
                });
            } else {
                $this->query->whereHas('metering_device', function ($query) use ($deviceId) {
                    $query->where('id', $deviceId);
                });
            }
        }
        return $this;
    }

    /**
     * отбор по типу тарифа у прибора
     *
     * @param $deviceId
     * @return $this
     */
    public function for_rate_type($rate_type)
    {
        if ($rate_type) {
            if (is_array($rate_type)) {
                $this->query->whereHas('metering_device', function ($query) use ($rate_type) {
                    $query->whereIn('rate_type_id', $rate_type);
                });
            } else {
                $this->query->whereHas('metering_device', function ($query) use ($rate_type) {
                    $query->where('rate_type_id', $rate_type);
                });
            }
        }
        return $this;
    }


    /**
     * отбор по дате
     *
     * @param $date_start
     * @param $date_end
     * @return $this
     */
    public function between_date($date_start, $date_end)
    {
        if ($date_start) {
            $this->query->where('date', '>=', $date_start);
        }
        if ($date_end) {
            $this->query->where('date', '<', $date_end);
        }
        return $this;
    }

    /**
     * отбор по счету
     *
     * @param $invoice_id
     * @return $this
     */
    public function for_invoice($invoice_id)
    {
        if ($invoice_id) {
            $this->query->where('invoice_id', $invoice_id);
        }
        return $this;
    }

    /**
     * отбор по платежу
     *
     * @param $payment_id
     * @return $this
     */
    public function for_payment($payment_id)
    {
        if ($payment_id) {
            $this->query->where('payment_id', $payment_id);
        }
        return $this;
    }


    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        $this->query->where($column, $operator, $value, $boolean);
        return $this;
    }

    public function orderBy($column, $direction = 'asc')
    {
        $this->query->orderBy($column, $direction);
        return $this;
    }

    public function first()
    {
        return $this->query->first();
    }

    public function get()
    {
        return $this->query->get();
    }

    public function paginate($limit)
    {
        return $this->query->orderBy('sort')->paginate($limit);
    }

}