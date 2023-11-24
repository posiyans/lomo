<?php

namespace Database\Seeders;

use App\Models\Laratrust\Permission;
use App\Models\Laratrust\Role;
use App\Models\User;
use App\Modules\Comment\Repositories\CommentRepository;
use App\Modules\SiteMenu\Actions\CreateSiteMenuAction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Laratrust\Models\LaratrustPermission;
use Laratrust\Models\LaratrustRole;

class SiteMenuSeeder extends Seeder
{
    /**
     * Заполняем начальное меню сайта
     *
     * @return  void
     */
    public function run()
    {
        echo 'SiteMenuSeeder ' . "\n";
        $menu= [
            [
              'label' => 'Главная',
              'path' => '/',
              'sort' => 1,
            ],
            [
                'label' => 'Информация',
                'path' => '',
                'sort' => 2,
                'children' => [
                    [
                        'label' => 'Тарифы',
                        'path' => '/modules/rates',
                        'sort' => 1,
                    ],
                    [
                        'label' => 'Реквизиты для оплаты',
                        'path' => '/modules/receipt',
                        'sort' => 2,
                    ],
                    [
                        'label' => 'Расписание',
                        'path' => '/yandex/schedule',
                        'sort' => 3,
                    ],
                    [
                        'label' => 'Погода',
                        'path' => '/weather/show',
                        'sort' => 4,
                    ],
                    [
                        'label' => 'Камеры',
                        'path' => '/camera/show',
                        'sort' => 5,
                    ],
                ]
            ]
        ];

        foreach ($menu as $item) {
            $this->createMenu($item);
        }
    }

    protected function createMenu($item, $parent = null)
    {

       $menu = (new CreateSiteMenuAction($item['label']))->path($item['path'])->sort($item['sort'])->parent($parent)->run();
       if (isset($item['children']) && is_array($item['children'])) {
           foreach ($item['children'] as $child) {
               $this->createMenu($child, $menu);
           }
       }
    }



}
