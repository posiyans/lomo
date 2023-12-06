<?php

namespace App\Modules\Comment\Classes;

use App\Modules\Article\Models\ArticleModel as Model;
use App\Modules\Comment\Interfaces\CommentedObjectInterface;
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
     * возможность читать комментариии обьекта пользователем
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
     * возможность оставлять комментариии для обьекта пользователем
     * @param $user UserModel
     * @return mixed
     */
    public function commentWrite(UserModel $user): bool
    {
        return false;
    }

    /**
     * возможность править комментариии обьекта пользователем
     * @param $user UserModel
     * @return mixed
     */
    public function commentEdit(UserModel $user): bool
    {
        return false;
    }

    /**
     * возможность удалять комментариии обьекта пользователем
     *
     * @param $user UserModel
     * @return mixed
     */
    public function commentDelete(UserModel $user): bool
    {
        return $this->commentWrite($user);
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