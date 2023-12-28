<?php

namespace App\Modules\Rate\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RateTypeResource extends JsonResource
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
        $data['rate'] = $this->currentRate ?? ['ratio_a' => '', 'ratio_b' => '', 'date_start' => ''];
        $data['depends'] = $this->rateGroup->depends;
        $data['unit_name'] = $this->rateGroup->options['unit_name'] ?? '';
        return $data;
    }
}
