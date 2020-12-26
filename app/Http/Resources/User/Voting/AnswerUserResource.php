<?php

namespace App\Http\Resources\User\Voting;

use Illuminate\Http\Resources\Json\JsonResource;

class AnswerUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'question_id' => $this->question_id,
            'text' => $this->text,
        ];
    }
}
