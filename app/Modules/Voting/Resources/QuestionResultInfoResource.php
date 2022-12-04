<?php

namespace App\Modules\Voting\Resources;

use App\Modules\Stead\Repositories\GetSteadRepository;
use App\Modules\Voting\Repositories\GetAllAnswersForQuestionRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResultInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $all_answer = count((new GetAllAnswersForQuestionRepository($this->resource))->run());
        $count_stead = count((new GetSteadRepository())->run());
        return [
            'id' => $this->id,
            'title' => $this->text,
            'all_answer' => $all_answer,
            'count_stead' => $count_stead,
            'procent' => round(100 * $all_answer / $count_stead, 2),
            'answers' => AnswerResultInfoResource::collection($this->answers),
        ];

    }
}
