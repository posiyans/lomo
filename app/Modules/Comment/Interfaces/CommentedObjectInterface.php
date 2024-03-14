<?php

namespace App\Modules\Comment\Interfaces;

use App\Modules\User\Models\UserModel;

/**
 */
interface CommentedObjectInterface
{

    /**
     * описание что комментируется
     * @return array
     */
    public function descriptionForComment(): array;

    /**
     * возможность читать комментарии обьекта пользователем
     *
     * @return boolean|\Exception
     */

    /**
     * @param $user UserModel|null
     * @return mixed
     */
    public function commentRead($user);

    /**
     * возможность оставлять комментарии для обьекта пользователем
     * @param $user UserModel
     * @return mixed
     */
    public function commentWrite(UserModel $user);

    /**
     * возможность править(удалять) комментарии обьекта пользователем
     * @param $user UserModel
     * @return mixed
     */
    public function commentEdit(UserModel $user);


    /**
     * возможность удалять комментарии обьекта пользователем
     * @param $user UserModel
     * @return mixed
     */
    public function commentDelete(UserModel $user);

    /**
     * возможность оправлять пользователей в бан
     *
     * @param $user UserModel
     * @return mixed
     */
    public function commentUserBan(UserModel $user);


}
