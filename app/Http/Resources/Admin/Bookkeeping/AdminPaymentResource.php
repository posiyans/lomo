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
        $type = $this->getType;
        $data['type_name'] = '';
        $data['type_depends'] = false;
        if ($type) {
            $data['type_name'] = $type->name;
            if ($type->depends == 2) {
                $data['type_depends'] = true;
                $dep = $type->MeteringDevice;
                $dep_temp = [];
                foreach ($dep as $item) {
                  $dep_temp[] = ['id' => $item->id, 'name' => $item->name];
                }

                $data['depends'] = $dep_temp;
                $tmp = [];
                if ($this->type == 1) {
                    foreach ($this->instrumentReadings as $item) {
                        $tmp['d'.$item->device_id] = ['device' => $item->device_id, 'value'=>$item->value];
                    }
                }
                $data['instr_read'] = $tmp;
            }
        }



        return $data;
    }
}
