<?php

namespace App\Modules\Billing\Resources;

use App\Modules\MeteringDevice\Resources\InstrumentReadingSmallResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
        if ($this->stead_id) {
            $data['stead'] = [
                'id' => $this->stead->id,
                'number' => $this->stead->number,
                'size' => $this->stead->size,
            ];
        } else {
            $data['stead'] = null;
        }
        if ($this->rate_group_id) {
            $data['rate'] = [
                'id' => $this->rate_group_id,
                'name' => $this->rate_group->name,
                'depends' => $this->rate_group->depends,
            ];
        } else {
            $data['rate'] = null;
        }
        $data['readings'] = InstrumentReadingSmallResource::collection($this->instrument_readings);

        return $data;
    }
}
