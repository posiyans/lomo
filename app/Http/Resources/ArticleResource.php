<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        $roles = array_map(function ($i) {return $i;} , get_object_vars($this->roles));
//        $roles = [];
//        foreach ($this->roles as $role){
//            $roles[] = $role->name;
//        }
//        $permissions = [];
//        foreach ($this->permissions as $permission){
//            $permissions[] = $permission->name;
//        }
        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'title' => $this->title,
            'resume' => $this->resume,
            'text' => $this->text,
            'category_id' => $this->category_id,
            'files' => $this->files,
            'allow_comments' => $this->allow_comments,
            'public' => $this->public,
            'publish_time' => $this->publish_time,
            'comments' => CommentResource::collection($this->comments),
//            'publish_time' => $this->publish_time,
//            'middle_name' => $this->middle_name,
//            'email' => $this->email,
//            'email_verified_at' => $this->email_verified_at ? true : false,
//            'phone' => $this->phone,
//            'adres' => $this->adres,
//            'avatar' => $this->avatar,
//            'steads' => $this->steads ? $this->steads : [],
//            'created_at' => $this->created_at,
//            'consent_to_email' => $this->consent_to_email,
//            'consent_personal' => $this->consent_personal,
//            'last_connect' => $this->last_connect,
//            'roles' => ['roles'=>$roles, 'permissions'=>$permissions],

        ];

//        return parent::toArray($request);
    }
}
