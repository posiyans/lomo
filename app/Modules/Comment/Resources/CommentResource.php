<?php

namespace App\Modules\Comment\Resources;

use App\Modules\Comment\Repositories\GetDataForObject;
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
            'options' => $this->options,
            'user' => [
                'uid' => $user->uid,
                'name' => $user->last_name . ' ' . $user->name,
            ],
            'parentObject' => [
                'type' => (new GetDataForObject($this->parentModel))->label(),
                'url' => (new GetDataForObject($this->parentModel))->url(), // todo переделать чтоб работало в зависимости от родительской модели,
            ],

        ];
        return $data;
    }
}
