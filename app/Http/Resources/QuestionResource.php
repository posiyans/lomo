<?php

namespace App\Http\Resources;

use App\Models\Stead;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'text'=>$this->text,
            'voting_id'=>$this->voting_id,
            'answers'=>AnswerResource::collection($this->answers),
            'answersCount'=>$this->allAnswers(),

//            'answers'=>$this->answers
        ];
//        $this->user->fullName = $this->user->fullName();;
//        $this->user->fName = $this->user->fName();;
//        $this->answers;
//        return parent::toArray($request);
    }
}
