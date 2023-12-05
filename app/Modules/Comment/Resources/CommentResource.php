<?php

namespace App\Modules\Comment\Resources;

use Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = Auth::user();
        $data = [
            'id' => $this->id,
            'uid' => $this->uid,
            'message' => $this->message,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'options' => $this->options,
            'user' => [
                'uid' => $this->user ? $this->user->uid : '',
                'name' => $this->user ? $this->user->last_name . ' ' . $this->user->name : 'Bot',
            ],
            'parentObject' => $this->parentModel->descriptionForComment(),
            'actions' => [
                'delete' => $user ? $this->parentModel->commentEdit($user) : false,
                'ban' => $user ? $this->parentModel->commentUserBan($user) : false,
                'write' => $user ? $this->parentModel->commentWrite($user) : false,
            ]

        ];
        return $data;
    }
}
