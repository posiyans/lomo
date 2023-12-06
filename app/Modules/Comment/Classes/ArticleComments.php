<?php

namespace App\Modules\Comment\Classes;

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
        if ($user && $user->ability('superAdmin', ['article->edit', 'article->show', 'comment-edit', 'comment-ban', 'comment-delete'])) {
            return true;
        }
        return $this->object->status == 1;
    }

    public function commentWrite($user): bool
    {
        if ($this->object->allow_comments == 0) {
            return false;
        }
        if ($user->ability('superAdmin', ['article->edit', 'article->show', 'comment-edit', 'comment-ban', 'comment-delete'])) {
            return true;
        }
        if ($this->object->allow_comments == 2) {
            return !!$user->owner;
        }
        return $this->object->status == 1 && $user->email_verified_at;
    }

    public function commentEdit($user): bool
    {
        return $user->ability('superAdmin', ['article->edit', 'comment-edit', 'comment-ban', 'comment-delete']);
    }

    public function commentUserBan($user): bool
    {
        return $user->ability('superAdmin', ['comment-ban']);
    }

}