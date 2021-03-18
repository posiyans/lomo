<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class SteadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $owners = $this->owners;
        $ownre_user = [];
        foreach ($owners as $owner) {
            $user = Auth::user();
            if ($user->ability(['superAdmin'], ['access-to-personal'])) {
                $owner_name = $owner->owner->fullName();
            } else {
                $owner_name = $owner->owner->smalllName();
            }
            $ownre_user[] = [
                'fullName' => $owner_name,
                'uid' => $owner->owner_uid,
                'proportion' => $owner->proportion
            ];
        }
        return [
            'id'=> $this->id,
            'number'=>$this->number,
            'size'=>$this->size,
            'owners' => $ownre_user,
            'user_fullName'=>optional($this->user)->fullName(),
            'user_fName'=>optional($this->user)->fName(),
            'user'=> $this->user ? $this->user : [],
            'descriptions' => $this->descriptions
        ];
    }
}
