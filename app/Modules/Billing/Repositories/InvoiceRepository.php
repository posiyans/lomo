<?php

namespace App\Modules\Billing\Repositories;

use App\Modules\Billing\Models\BillingInvoiceModel;

class InvoiceRepository
{

    private $query;

    public function __construct()
    {
        $this->query = BillingInvoiceModel::query();
    }

    /**
     * @param $id
     * @return \App\Modules\Billing\Models\BillingInvoice|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
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

    public function forRateGroup($group_id = false)
    {
        if ($group_id) {
            $this->query->where('rate_group_id', $group_id);
        }
        return $this;
    }

    public function isPaid($isPaid = 1)
    {
        if ($isPaid === '1') {
            $this->query->where('is_paid', true);
        } elseif ($isPaid == '0') {
            $this->query->where('is_paid', false);
        }
        return $this;
    }


    public function paginate($limit)
    {
        return $this->query
            ->orderBy('id', 'DESC')
            ->paginate($limit);
    }

    public function get()
    {
        return $this->query
            ->orderBy('id', 'DESC')
            ->get();
    }


}