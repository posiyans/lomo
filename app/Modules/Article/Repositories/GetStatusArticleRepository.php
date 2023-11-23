<?php

namespace App\Modules\Article\Repositories;

class GetStatusArticleRepository
{

    private $status = [
        [
            'id' => 0,
            'label' => 'черновик',
            'color' => '#cecece'
        ],
        [
            'id' => 1,
            'label' => 'опубликовано',
            'color' => '#005500'
        ],
        [
            'id' => 2,
            'label' => 'на модерации',
            'color' => '#0000ff'
        ],
        [
            'id' => 3,
            'label' => 'на доработке',
            'color' => '#cecece'
        ],
        [
            'id' => 9,
            'label' => 'отказано',
            'color' => '#ff0000'
        ],
    ];

    public function __construct()
    {
    }

    public function run()
    {
        return $this->status;
    }


}