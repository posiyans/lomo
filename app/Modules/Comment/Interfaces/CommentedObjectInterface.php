<?php

namespace App\Modules\Comment\Interfaces;

use App\Modules\User\Models\UserModel;

interface CommentedObjectInterface
{


    /**
     * описание что комментируется
     * @return array
     */
    public function descriptionForComment(): array;

    /**
     * возможность читать комментариии обьекта пользователем
     *
     * @return boolean|\Exception
     */

    /**
     * @param $user UserModel|null
     * @return mixed
     */
    public function commentRead($user);

    /**
     * возможность оставлять комментариии для обьекта пользователем
     * @param $user UserModel
     * @return mixed
     */
    public function commentWrite(UserModel $user);

    /**
     * возможность править(удалять) комментариии обьекта пользователем
     * @param $user UserModel
     * @return mixed
     */
    public function commentEdit(UserModel $user);

    /**
     * возможность оправлять пользователей в бан
     *
     * @param $user UserModel
     * @return mixed
     */
    public function commentUserBan(UserModel $user);

    /**
     * получить модель автора комментария
     *
     * @return UserModel|null
     */
    public function user();

}
