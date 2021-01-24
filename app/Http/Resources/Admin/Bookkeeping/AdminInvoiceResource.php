<?php

namespace App\Http\Resources\Admin\Bookkeeping;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminInvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['stead_number'] = $this->steadNumber();
        $data['metersData'] = $this->metersData;
        $data['type_name'] = $this->receiptType->name;
        return ['status'=>true, 'data'=>$data];
    }
}
