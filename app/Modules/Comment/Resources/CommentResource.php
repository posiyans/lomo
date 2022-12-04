<?php

namespace App\Modules\Comment\Resources;

use App\Modules\User\Repositories\GetUserByUidRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = (new GetUserByUidRepository($this->user_id))->run();
        $data = [
            'id' => $this->id,
            'uid' => $this->uid,
            'message' => $this->message,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => [
                'id' => $this->user_id,
                'name' => $user->last_name . ' ' . $user->name,
            ],

        ];
        return $data;

    }
}
