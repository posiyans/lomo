<?php

namespace App\Modules\Social\Repositories;

use App\Modules\Social\Models\LinkedSocialAccounts;

/**
 * Получить отношение к соц сетям  по социальному id
 */
class GetSocialAccountBySocialData
{
    private $query;

    public function __construct($social_type, $social_id)
    {
        $this->query = LinkedSocialAccounts::where('provider_id', $social_id)->where('provider_name', $social_type);
    }

    public function run()
    {
        $model = $this->query->orderBy('id', 'desc')->first();
        if ($model) {
            return $model;
        }
        throw new \Exception('Пользователь не найден');
    }


}