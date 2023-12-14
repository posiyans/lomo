<?php

namespace App\Modules\Article\Actions;

use App\Modules\Article\Models\ArticleModel;
use App\Modules\Setting\Actions\GetGlobalOption;
use App\Modules\User\Models\UserModel;

/**
 * получить возможность пользователю  предлогать статью
 *
 */
class AllowPublicationArticleAction
{
    private $access;

    public function __construct($access = false)
    {
        if ($access) {
            $this->access = $access;
        } else {
            $this->access = GetGlobalOption::getOneValue(ArticleModel::getAllowPublicationArticleSettingName(), 1);
        }
    }

    public function run(UserModel $user)
    {
        // 1 - отключенно
        // 2 - собственники
        // 3 - все
        $errors = 'Неизвестная причина';
        switch ($this->access) {
            case 1:
                $errors = 'Возможность отключенна';
                break;
            case 2:
                $errors = 'Только собственники';
                break;
            case 3:
                $errors = 'Пользователь с подтвержденным email';
                break;
        }
        if ($this->access === 3) {
            if ($user->email_verified_at) {
                return true;
            }
        }
        if ($this->access === 2 && $user->hasRole('owner')) {
            return true;
        }
        throw new \Exception($errors);
    }
}
