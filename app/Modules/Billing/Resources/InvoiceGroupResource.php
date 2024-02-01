<?php

namespace App\Modules\Billing\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        $data['invoices'] = [
            'total' => $this->invoices->count(),
            'price' => round($this->invoices->sum('price'), 2),

            'paid' => [
                'price' => $this->invoices->filter(function ($value, $key) {
                    return $value->is_paid;
                })->sum('price'),
                'total' => $this->invoices->filter(function ($value, $key) {
                    return $value->is_paid;
                })->count(),
            ],
        ];
//        $data['stead'] = [
//            'id' => $this->stead->id,
//            'number' => $this->stead->number,
//            'size' => $this->stead->size,
//        ];
//        $data['payments'] = $this->payments;
//        if ($this->rate_group_id) {
//            $data['rate'] = [
//                'id' => $this->rate_group_id,
//                'name' => $this->rate_group->name,
//            ];
//        } else {
//            $data['rate'] = null;
//        }
        return $data;
    }
}
