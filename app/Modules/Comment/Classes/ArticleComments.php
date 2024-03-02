<?php

namespace App\Modules\Comment\Classes;

use App\Modules\Comment\Models\CommentModel;
use App\Modules\User\Models\UserModel;

class ArticleComments extends AbstractComments
{

    public function descriptionForComment(): array
    {
        return [
            'label' => 'записи ' . $this->object->title,
            'url' => '/article/show/' . $this->object->slug,
        ];
    }

    public function commentRead($user): bool
    {
        if ($user && $user->ability('superAdmin', ['article->edit', 'article->show', 'comment-ban', 'comment-delete'])) {
            return true;
        }
        return $this->object->status == 2;
    }

    public function commentWrite($user): bool
    {
        if (in_array($this->object->status, [2, 4])) {
            if ($this->object->allow_comments == 1) {
                return false;
            }
            if ($user->ability('superAdmin', ['article->edit', 'article->show', 'comment-ban', 'comment-delete'])) {
                return true;
            }
            if ($this->object->allow_comments == 2) {
                return !!$user->email_verified_at;
            }
            if ($this->object->allow_comments == 3) {
                return !!$user->owner;
            }
        }
        return false;
    }

    public function commentEdit($user): bool
    {
        return $user->ability('superAdmin', ['article->edit', 'comment-ban', 'comment-delete']);
    }

    public function commentUserBan($user): bool
    {
        return $user->ability('superAdmin', ['comment-ban']);
    }

    /**
     * возможность удалять комментариии обьекта пользователем
     *
     * @param $user UserModel
     * @return mixed
     */
    public function commentDelete(UserModel $user, CommentModel|null $comment = null): bool
    {
        if ($user->ability('superAdmin', ['comment-delete'])) {
            return true;
        }
        if ($user->id == $comment->user_id) {
            return true;
        }
        return false;
    }

}