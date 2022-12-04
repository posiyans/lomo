<?php

namespace App\Modules\Voting\Resources;

use App\Modules\Stead\Repositories\GetSteadRepository;
use App\Modules\Voting\Repositories\GetAllAnswersForQuestionRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionAndAnswersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['answers'] = $request->request->answers;
        return $data;

    }
}
