<?php
namespace App\Modules\Stead\Models;

use App\Models\MyModel;


/**
 * Модель участков
 */
class SteadModel extends MyModel
{

//    protected $fillable = ['number', 'descriptions'];
    protected $casts = [
        'descriptions' => 'array',
        'options' => 'array',
    ];

    protected $table = 'steads';



}
