<?php

namespace App\Modules\BanUser\Resources;

use App\Modules\BanUser\Classes\IsBanUserAdminClass;
use App\Modules\BanUser\Repositories\BanUserTypeRepository;
use App\Modules\User\Repositories\GetUserByUidRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class BanUserResource extends JsonResource
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
            'end_ban_time' => $this->end_ban_time,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'description' => '',
            'user' => [
                'uid' => $user->uid,
                'name' => $user->last_name . ' ' . $user->name,
            ],
            'type' => [
                'key' => BanUserTypeRepository::getKeyForClass($this->commentable_type),
                'label' => BanUserTypeRepository::getLabelForClass($this->commentable_type),
                'uid' => $this->commentable_uid,
            ],
            'author' => []
        ];
        $myUser = \Auth::user();
        if (IsBanUserAdminClass::isAdmin($myUser)) {
            $data['description'] = $this->description;
            $author = (new GetUserByUidRepository($this->author_id))->run();
            $data['author'] = [
                'uid' => $author->uid,
                'name' => $author->last_name . ' ' . $author->name,
            ];
        }
        return $data;
    }
}
