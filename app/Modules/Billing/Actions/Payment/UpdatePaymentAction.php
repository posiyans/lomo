<?php

namespace App\Modules\Billing\Actions\Payment;

use App\Modules\Billing\Models\BillingPaymentModel;
use Illuminate\Support\Facades\Cache;

/**
 * обновить данные платежа
 *
 */
class UpdatePaymentAction
{

    private $payment;

    public function __construct(BillingPaymentModel $payment)
    {
        $this->payment = $payment;
    }

    /**
     * изменить участок
     *
     * @param $stead_id
     * @return $this
     */
    public function setStead($stead_id): UpdatePaymentAction
    {
        $this->payment->stead_id = $stead_id;
        return $this;
    }

    /**
     * изменить группу тарифов
     *
     * @param $rate_group_id
     * @return $this
     */
    public function setRateGroup($rate_group_id): UpdatePaymentAction
    {
        $this->payment->rate_group_id = $rate_group_id;
        return $this;
    }

    /**
     * изменить примечание
     *
     * @param $description
     * @return $this
     */
    public function setDescription($description): UpdatePaymentAction
    {
        $this->payment->description = $description;
        return $this;
    }

    /**
     * Установиь что платеж проверен
     *
     * @return $this
     */
    public function setDataVerified(): UpdatePaymentAction
    {
        $this->payment->error = false;
        return $this;
    }


    /**
     * @throws \Exception
     */
    public function run(): BillingPaymentModel
    {
        if ($this->payment->logAndSave('Изменние платежа')) {
            Cache::tags(['payment'])->flush();
            return $this->payment;
        }
        return throw  new \Exception('Ошибка измения платежа');
    }

}
