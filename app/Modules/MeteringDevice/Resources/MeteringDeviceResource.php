<?php

namespace App\Modules\MeteringDevice\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MeteringDeviceResource extends JsonResource
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
        $data = array_merge($data, $this->options);
        $rate = [
            'id' => $this->rate_type->id,
            'name' => $this->rate_type->name,
            'description' => $this->rate_type->description,
            'group_name' => $this->rate_type->rate_group->name,
            'unit_name' => $this->rate_type->rate_group->options['unit_name'] ?? '',
        ];
        $data['last_reading'] = $this->last_reading();  // todo  Убрать отсюда
        $data['rate'] = $rate;
        return $data;
    }
}
