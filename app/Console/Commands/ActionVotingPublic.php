<?php

namespace App\Console\Commands;

use App\Models\Stead;
use App\Models\Voting\AnswerModel;
use App\Models\Voting\UserAnswerModel;
use App\Models\Voting\VotingModel;
use App\Models\User;
use Illuminate\Console\Command;

class ActionVotingPublic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:public-voting-action {voting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Провести фейковое публичное голосование';

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
        $users = User::all();
        $i= 0;
        $j = 0;
        $voting = VotingModel::find($id);
        $questions = $voting->questions;
        if ($voting) {
            foreach ($users as $user) {
                if (rand(0, 11) > 3) {
                    echo $user->id . " -->> голосует \n";
                    $i++;
                    foreach ($questions as $question) {
                        $answer = AnswerModel::where('question_id', $question->id)->inRandomOrder()->first();
                        echo $answer->id  . " -->> ответ \n";
                        $rez = new UserAnswerModel();
                        $rez->question_id = $question->id;
                        $rez->answer_id = $answer->id;
                        $rez->user_id = $user->id;
                        $rez->stead_id = null;
                        $rez->save();
                    }
                } else {
                    echo $user->id . " воздержался \n";
                }
                $j++;
            }
        }
        echo (int)(100*$i/$j). " % проголосовали \n";
    }
}
