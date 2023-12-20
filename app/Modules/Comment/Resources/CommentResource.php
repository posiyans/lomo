<?php

namespace App\Modules\Comment\Resources;

use App\Modules\Comment\Repositories\CommentTypeRepository;
use App\Modules\File\Resources\FileResource;
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
        $parentModel = $this->parentModel;
        $commentedModel = CommentTypeRepository::getCommentedRoleObject($parentModel);
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
            'files' => FileResource::collection($this->files),
            'parentObject' => $commentedModel->descriptionForComment(),
            'actions' => [
                'edit' => $user ? $commentedModel->commentEdit($user) : false,
                'delete' => $user ? $commentedModel->commentDelete($user) : false,
                'ban' => $user ? $commentedModel->commentUserBan($user) : false,
                'write' => $user ? $commentedModel->commentWrite($user) : false,
            ]

        ];
        return $data;
    }
}
