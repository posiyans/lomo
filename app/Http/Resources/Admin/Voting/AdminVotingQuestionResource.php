<?php

namespace App\Http\Resources\Admin\Voting;

use App\Models\Stead;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminVotingQuestionResource extends JsonResource
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
            'answers' => AdminVotingAnswerResource::collection($this->answers),
            'answersCount' => $this->allAnswers(),

//            'answers'=>$this->answers
        ];
//        $this->user->fullName = $this->user->fullName();;
//        $this->user->fName = $this->user->fName();;
//        $this->answers;
//        return parent::toArray($request);
    }
}
