<?php

namespace App\Modules\Comment\Classes;

use App\Modules\Article\Models\ArticleModel as Model;
use App\Modules\Comment\Interfaces\CommentedObjectInterface;
use App\Modules\Comment\Models\CommentModel;
use App\Modules\User\Models\UserModel;

abstract class AbstractComments implements CommentedObjectInterface
{

    protected $object;

    public function __construct(Model $object)
    {
        $this->object = $object;
    }


    public function descriptionForComment(): array
    {
        return [
            'label' => '',
            'url' => '',
        ];
    }

    /**
     * возможность читать комментарии обьекта пользователем
     *
     * @return boolean|\Exception
     */

    /**
     * @param $user UserModel|null
     * @return mixed
     */
    public function commentRead($user): bool
    {
        return true;
    }

    /**
     * возможность оставлять комментарии для обьекта пользователем
     * @param $user UserModel
     * @return mixed
     */
    public function commentWrite(UserModel $user): bool
    {
        return false;
    }

    /**
     * возможность править комментарии обьекта пользователем
     * @param $user UserModel
     * @return mixed
     */
    public function commentEdit(UserModel $user): bool
    {
        return false;
    }

    /**
     * возможность удалять комментарии обьекта пользователем
     *
     * @param $user UserModel
     * @return mixed
     */
    public function commentDelete(UserModel $user, CommentModel|null $comment = null): bool
    {
        return false;
    }

    /**
     * возможность оправлять пользователей в бан
     *
     * @param $user UserModel
     * @return mixed
     */
    public function commentUserBan(UserModel $user): bool
    {
        return false;
    }


}