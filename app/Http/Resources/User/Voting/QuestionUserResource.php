<?php

namespace App\Http\Resources\User\Voting;

use App\Http\Resources\User\Voting\AnswerUserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionUserResource extends JsonResource
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
            'id' => $this->id,
            'text' => $this->text,
            'voting_id' => $this->voting_id,
            'answers' => AnswerUserResource::collection($this->answers),
        ];
    }
}
