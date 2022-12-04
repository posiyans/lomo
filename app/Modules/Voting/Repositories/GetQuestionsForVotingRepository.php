<?php
namespace App\Modules\Voting\Repositories;


use App\Modules\Voting\Models\QuestionModel;
use App\Modules\Voting\Models\VotingModel;

class GetQuestionsForVotingRepository {

    private $query;

    public function __construct(VotingModel $voting)
    {
        $this->query = QuestionModel::where('voting_id', $voting->id);
    }

    public function run()
    {
        return $this->query->get();
    }

}