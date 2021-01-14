<?php

namespace App\Http\Resources\Admin\Bookkeeping;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminDeviceRegisterResource extends JsonResource
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
        $data['type_name'] = $this->getTypeName();
        $reading = $this->getLastReading();
        if ($reading) {
            $data['last_reading'] = $reading;
        } else {
           $data['last_reading'] = false;
        }

         return $data;
    }
}
