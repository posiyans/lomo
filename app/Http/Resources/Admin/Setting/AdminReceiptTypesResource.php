<?php

namespace App\Http\Resources\Admin\Setting;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminReceiptTypesResource extends JsonResource
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
        if (!is_array($data['options']) || count($data['options']) == 0) {
            $data['options'] = ['payment_date'=> '', 'payment_month' => '' , 'tag' => []];
        }
        return $data;
    }
}
