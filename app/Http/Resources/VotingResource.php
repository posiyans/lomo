<?php

namespace App\Http\Resources;

use App\Models\Stead;
use Illuminate\Http\Resources\Json\JsonResource;

class VotingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $datetime = new \DateTime();
        $status = $this->status;
        if ($this->status == 'new') {
            if ($this->type == 'public'){
                if (strtotime($this->date_publish) < $datetime->format('U')) {
                    $status = 'execution';
                }
            } else {
                if (strtotime($this->date_start) < $datetime->format('U')) {
                    $status = 'execution';
                }
                if (strtotime($this->date_stop) < $datetime->format('U')) {
                    $status = 'done';
                }
            }
        }
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'description'=>$this->description,
            'date_publish'=>$this->date_publish,
            'date_start'=>$this->date_start,
            'date_stop'=>$this->date_stop,
            'type'=>$this->type,
            'comments'=>$this->comments,
            'status'=>$status,
            'public'=>$this->public,
            'files'=>$this->files,
            'countAnswer'=>$this->userAnswer(),
            'questions'=>QuestionResource::collection($this->questions),
            'steadsCount'=> Stead::all()->count(),
            'created_at'=>$this->updated_at,
            'dateRange' => [$this->date_start, $this->date_stop]
            ];
    }
}
