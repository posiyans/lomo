<?php
namespace App\Modules\Voting\Repositories;


use App\Modules\Voting\Models\QuestionModel;

class GetAllAnswersForQuestionRepository {

    private $question;

    public function __construct(QuestionModel $question)
    {
        $this->question = $question;
    }

    public function run()
    {
        $userAnswer = [];
        foreach ($this->question->answers as $answer) {
//                $userAnswer[] = $answer->userAnswers;
            foreach ($answer->userAnswers as $item) {
                $userAnswer[] = $item;
            }

        }
        return $userAnswer;
    }


}