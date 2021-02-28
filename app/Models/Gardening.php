<?php
namespace App\Models;

use App\Models\MyModel;

/**
 * Модель для садоводсва
 */
class Gardening extends MyModel
{
    protected $fillable = ['BankName', 'BIC', 'CorrespAcc', 'full_name', 'name', 'PayeeINN', 'PersonalAcc'];
    //
}
