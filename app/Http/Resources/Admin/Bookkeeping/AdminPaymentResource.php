<?php

namespace App\Http\Resources\Admin\Bookkeeping;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminPaymentResource extends JsonResource
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
        $data['stead'] = $this->steadObject(['id', 'number']);
        $tmp = [];
        if ($this->type == 1) {
            foreach ($this->instrumentReadings as $item) {
                $tmp['d'.$item->device_id] = ['device' => $item->device_id, 'value'=>$item->value];
            }
        }
        $data['instr_read'] = $tmp;
        return $data;
    }
}
