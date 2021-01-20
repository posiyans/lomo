<?php

namespace App\Http\Resources\Admin\Bookkeeping;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminInstrumentReadingsResource extends JsonResource
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
        $data['price'] = $this->getPrice();
        $data['type_name'] = $this->deviceTypeName();
        $temp = $this->getPreviousReadings();
        $data['prev_value'] = $temp;
        $data['delta'] = $this->value - $temp;
        $data['summa'] = ($this->value - $temp) * $data['price'];

        return $data;
    }
}
