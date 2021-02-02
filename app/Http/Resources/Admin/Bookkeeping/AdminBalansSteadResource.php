<?php

namespace App\Http\Resources\Admin\Bookkeeping;

use App\Models\Receipt\ReceiptType;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminBalansSteadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $types = ReceiptType::getReceiptTypeIds();
        $temp_balans = [];
        foreach ($types  as $type) {
            $temp_balans['d'.$type] = round($this->getBalans($type), 2);
        }
        return [
            'id'=>$this->id,
            'number' => $this->number,
            'size' => $this->size,
            'balans_all' => round($this->getBalans(), 2),
            'balans' => $temp_balans,
            'last_payment' => $this->lastPayment,

        ];

    }
}
