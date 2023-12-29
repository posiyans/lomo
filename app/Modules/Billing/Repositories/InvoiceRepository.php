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

    public function paginate($limit)
    {
        return $this->query->paginate($limit);
    }


}