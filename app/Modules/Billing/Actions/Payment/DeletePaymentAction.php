<?php

namespace App\Modules\Billing\Actions\Payment;

use App\Modules\Billing\Models\BillingPaymentModel;
use Illuminate\Support\Facades\Cache;

/**
 * удалить платеж
 *
 */
class DeletePaymentAction
{

    private $payment;

    public function __construct(BillingPaymentModel $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @throws \Exception
     */
    public function run(): bool
    {
        if ($this->payment->logAndDelete('Удалние платежа')) {
            Cache::tags(['payment'])->flush();
            return true;
        }
        return throw  new \Exception('Ошибка удаления платежа');
    }

}
