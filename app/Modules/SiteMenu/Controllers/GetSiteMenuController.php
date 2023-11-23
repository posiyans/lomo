<?php

namespace App\Modules\SiteMenu\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\SiteMenu\Repositories\SiteMenuRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * получить меня для пользователя
 *
 */
class GetSiteMenuController extends Controller
{


    public function __invoke(Request $request)
    {
        $cat = (new SiteMenuRepository())->menu();

        if (Auth::check()) {
            // todo личный кабинет
//            $cat[] = [
//                'label' => 'Личный кабинет',
//                'path' => '/personal-area',
//                'children' => [
//                    [
//                        'label' => 'Профиль',
//                        'path' => '/personal-area/profile',
//                    ],
//                ]
//            ];
            if (Auth::user()->ability('superAdmin', 'access-admin-panel')) {
                $cat[] = [
                    'label' => 'Админ панель',
                    'path' => '/admin',
                    'readOnly' => true,
                ];
            }
        }

        return $cat;
    }
}
