<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
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
//            'answer_id' => $this->answer_id,
//            'user_id' => $this->user_id,
//            'stead_id' => $this->stead_id,
//            'power_of_attorney_id' => $this->power_of_attorney_id,
//            'not_valid ' => $this->not_valid ,
//            'description' => $this->description,
//            'created_at' => $this->created_at,
            'userAnswersCount' => count($this->userAnswers),
            'userAnswers' => $this->getUserAnswers(),
        ];
//        $this->user->fullName = $this->user->fullName();;
//        $this->user->fName = $this->user->fName();;
//        $this->answers;
//        return parent::toArray($request);
    }
}
