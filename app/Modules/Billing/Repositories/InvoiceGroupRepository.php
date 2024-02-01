<?php

namespace App\Modules\Billing\Repositories;

use App\Modules\Billing\Models\BillingInvoiceGroupModel;

class InvoiceGroupRepository
{

    private $query;

    public function __construct()
    {
        $this->query = BillingInvoiceGroupModel::query();
    }

    /**
     * @param $id
     * @return \App\Modules\Billing\Models\BillingReestrModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function byId($id)
    {
        return $this->query->where('id', $id)->firstOrFail();
    }

    public function forRateGroup($group_id = false)
    {
        if ($group_id) {
            $this->query->where('rate_group_id', $group_id);
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