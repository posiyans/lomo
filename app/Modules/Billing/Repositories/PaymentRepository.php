<?php

namespace App\Modules\Billing\Repositories;

use App\Modules\Billing\Models\BillingPaymentModel;
use App\Modules\Stead\Models\SteadModel;

class PaymentRepository
{

    private $query;

    public function __construct()
    {
        $this->query = BillingPaymentModel::query();
    }

    /**
     * @param $id
     * @return \App\Modules\Billing\Models\BillingPaymentModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function byId($id)
    {
        return $this->query->where('id', $id)->firstOrFail();
    }

    public function forStead($stead_id = false)
    {
        if ($stead_id) {
            $this->query->where('stead_id', $stead_id);
        }
        return $this;
    }

    /**
     * отбор по наличию счета у платежа $invoice = 2 или его отсуствия $invoice = 1
     *
     * @param $invoice
     * @return $this
     */
    public function isInvoice($invoice)
    {
        if ($invoice) {
            if ($invoice == 1) {
                $this->query->whereNull('invoice_id');
            }
            if ($invoice == 2) {
                $this->query->whereNotNull('invoice_id');
            }
        }
        return $this;
    }

    public function findByPrice($price)
    {
        if ($price) {
            $this->query->where('price', $price);
        }
        return $this;
    }

    public function findByDate($date)
    {
        if ($date) {
            $this->query->where('payment_date', $date);
        }
        return $this;
    }

    public function findPayment($find = '')
    {
        if ($find) {
            $this->query->where(function ($query) use ($find) {
                $query->where('price', 'like', "%$find%");
                $query->orWhere('raw_data', 'like', "%$find%");
                $steads = SteadModel::query()->where('number', 'like', "%$find%")->get(['id']);
                $query->orWhereIn('stead_id', $steads);
            });
        }
        return $this;
    }

    public function forRateGroup($group_id = false)
    {
        if ($group_id) {
            $this->query->where('rate_group_id', $group_id);
        }
        return $this;
    }

    public function isError($isError = 1)
    {
        if ($isError === '1') {
            $this->query->where('error', true);
        } elseif ($isError == '0') {
            $this->query->where('error', false);
        }
        return $this;
    }


    public function forDate($date_start, $date_end)
    {
        if ($date_start) {
            $this->query->where('payment_date', '>=', $date_start);
        }
        if ($date_end) {
            $this->query->where('payment_date', '<=', $date_end);
        }
        return $this;
    }


    public function paginate($limit)
    {
        return $this->query
            ->orderBy('payment_date', 'desc')
            ->paginate($limit);
    }

    public function firstOrFail()
    {
        return $this->query->firstOrFail();
    }

    public function get()
    {
        return $this->query
            ->get();
    }


}