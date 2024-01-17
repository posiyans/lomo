<?php

namespace App\Modules\Billing\Repositories;

class BalansRepository
{

    private $payments;
    private $invoices;

    public function __construct()
    {
        $this->payments = [];
        $this->invoices = [];
    }


    private function getPrice()
    {
    }

}