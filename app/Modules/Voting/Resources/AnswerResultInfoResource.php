<?php

namespace App\Modules\Voting\Resources;

use App\Modules\Voting\Repositories\GetAllAnswersForQuestionRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResultInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $all = count((new GetAllAnswersForQuestionRepository($this->question))->run());
        $userAnswer = count($this->userAnswers);
        return [
            'title' => $this->text,
            'votes' => $userAnswer,
            'all_votes' => $all,
            'procent' => round(100 * $userAnswer / $all, 2),
        ];

    }
}
