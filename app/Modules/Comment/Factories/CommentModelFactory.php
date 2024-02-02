<?php

namespace App\Modules\Comment\Factories;

use App\Modules\Article\Models\ArticleModel;
use App\Modules\Comment\Models\CommentModel;
use App\Modules\User\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use function now;

class CommentModelFactory extends Factory
{
    protected $model = CommentModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $reply = null;
        $article = ArticleModel::query()->where('allow_comments', 2)->get()->random();
//        try {
//            $comment = $article->comments->random();
//            $rnd = rand(0, 10);
//            if ($comment && $rnd > 2) {
//                $reply = $comment->uid;
//            }
//        } catch (\Exception $e) {
//        }
        return [
            'uid' => Str::uuid(),
            'user_id' => UserModel::all()->random()->id,
            'commentable_type' => 'App\Modules\Article\Models\ArticleModel',
            'commentable_uid' => $article->uid,
            'message' => $this->faker->realText(),
            'created_at' => now(),
            'updated_at' => now(),
            'options' => [
                'reply' => $reply
            ]
        ];
    }

}
