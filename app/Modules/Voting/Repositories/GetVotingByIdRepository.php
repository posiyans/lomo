<?php
namespace App\Modules\Voting\Repositories;


use App\Modules\Voting\Models\VotingModel;

class GetVotingByIdRepository {

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function run()
    {

        $model = VotingModel::where('id', $this->id)->first();
        if ($model) {
            return $model;
        }
        throw new \Exception('Голосование не найдено    ');
    }

}