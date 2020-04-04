<?php

namespace App\Console\Commands;

use App\Models\Stead;
use App\Models\Voting\AnswerModel;
use App\Models\Voting\UserAnswerModel;
use App\Models\Voting\VotingModel;
use Illuminate\Console\Command;

class ActionVoting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:voting-action {voting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Провести фейковое голосование';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('voting');
        $steads = Stead::all();
        $i= 0;
        $j = 0;
        $voting = VotingModel::find($id);
        $questions = $voting->questions;
        if ($voting) {
            foreach ($steads as $stead) {
                if (rand(0, 11) > 3) {
                    echo $stead->number . " -->> голосует \n";
                    $i++;
                    foreach ($questions as $question) {
                        $answer = AnswerModel::where('question_id', $question->id)->inRandomOrder()->first();
                        echo $answer->id  . " -->> ответ \n";
                        $rez = new UserAnswerModel();
                        $rez->question_id = $question->id;
                        $rez->answer_id = $answer->id;
                        $rez->user_id = 1;
                        $rez->stead_id = $stead->id;
                        //$rez->save();
                    }
                } else {
                    echo $stead->number . " воздержался \n";
                }
                $j++;
            }
        }
        echo (int)(100*$i/$j). " % проголосовали \n";
    }
}
