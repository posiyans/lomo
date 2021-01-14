<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminSteadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $temp = [];
        if (isset($this->discriptions['geodata'])) {
            $js=$this->discriptions['geodata'];
            $temp = [
                'center' => [$js['properties']['center']['y'],$js['properties']['center']['x']],
            ];
            if (isset($js['geometry']['coordinates'])) {
                $ks = $js['geometry']['coordinates'][0][0];
                $l=[];
                foreach ($ks as $k) {
                    $l[]=[$k[1] + 0.00004500100000, $k[0] + 0.00006000100000];
                }
                $temp['krd'] = [$l, []];
            }
        }
        $data = parent::toArray($request);
        $data['coordinates'] = $temp;
        return $data;
    }
}
