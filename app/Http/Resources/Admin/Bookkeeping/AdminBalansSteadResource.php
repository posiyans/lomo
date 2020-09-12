<?php

namespace App\Http\Resources\Admin\Bookkeeping;

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
        return [
            'id'=>$this->id,
            'number' => $this->number,
            'size' => $this->size,
            'balans' => round($this->getBalans(), 2),
            'balans_1' => round($this->getBalans(1), 2),
            'balans_2' => round($this->getBalans(2), 2),
            'last_payment' => $this->lastPayment,

        ];

    }
}
