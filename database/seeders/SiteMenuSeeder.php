<?php

namespace Database\Seeders;

use App\Modules\SiteMenu\Actions\CreateSiteMenuAction;
use Illuminate\Database\Seeder;

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
        $menu = [
            [
                'label' => 'Главная',
                'path' => '/',
                'sort' => 1,
            ],
            [
                'label' => 'Новости',
                'path' => '/article/list/1',
                'sort' => 2,
            ],
            [
                'label' => 'Информация',
                'path' => '',
                'sort' => 3,
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
