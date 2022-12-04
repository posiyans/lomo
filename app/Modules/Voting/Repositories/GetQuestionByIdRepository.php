<?php
namespace App\Modules\Voting\Repositories;



use App\Modules\Voting\Models\QuestionModel;

class GetQuestionByIdRepository {

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function run()
    {

        $model = QuestionModel::where('id', $this->id)->first();
        if ($model) {
            return $model;
        }
        throw new \Exception('Вопрос не найден');
    }


}