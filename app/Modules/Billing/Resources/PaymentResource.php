<?php

namespace App\Modules\Billing\Resources;

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
            ];
        } else {
            $data['rate'] = null;
        }
//        $data['payments'] = $this->payments;

        return $data;
    }
}
