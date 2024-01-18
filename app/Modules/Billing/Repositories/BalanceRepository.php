<?php

namespace App\Modules\Billing\Repositories;

class BalanceRepository
{

    private $stead_id;
    private $rate_group_id;


    private $payments;
    private $invoices;

    public function __construct()
    {
        $this->stead_id = false;
        $this->rate_group_id = false;
        $this->payments = [];
        $this->invoices = [];
    }

    public function forStead($stead_id)
    {
        $this->stead_id = $stead_id;
        return $this;
    }

    public function forRateGroupId($rate_group_id)
    {
        $this->rate_group_id = $rate_group_id;
        return $this;
    }

    /*
     * пполучить сумму баланса
     */
    public function getPrice()
    {
        $invoice_price = $this->getInvoicePrice();
        $payment_price = $this->getPaymentPrice();
        return round($payment_price - $invoice_price, 2);
    }


    /**
     * Получить сумму всех счеитов
     *
     * @return mixed
     */
    private function getInvoicePrice()
    {
        $invoices = (new InvoiceRepository())
            ->forStead($this->stead_id)
            ->forRateGroup($this->rate_group_id)
            ->get();
        return $invoices->sum('price');
    }

    /**
     * Получить сумму всех платежей
     * @return mixed
     */
    private function getPaymentPrice()
    {
        $invoices = (new PaymentRepository())
            ->forStead($this->stead_id)
            ->forRateGroup($this->rate_group_id)
            ->get();
        return $invoices->sum('price');
    }


}