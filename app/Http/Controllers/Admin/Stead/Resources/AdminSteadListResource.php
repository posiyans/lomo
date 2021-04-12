<?php

namespace App\Http\Controllers\Admin\Stead\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminSteadListResource extends JsonResource
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
//        $owners = $this->owners;
        $owners = [];
        foreach ($this->owners as $owner) {
            $owners[] = [
                'name' =>$owner->owner->nameForMyRole(),
                'uid' =>$owner->owner->uid,
                ];
        }
        if (isset($this->descriptions['geodata'])) {
            $js=$this->descriptions['geodata'];
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
        $data['owners'] = $owners;
        $data['balans'] = round($this->getBalans(), 2);
        //$data['coordinates'] = $temp;
        return $data;
    }
}
