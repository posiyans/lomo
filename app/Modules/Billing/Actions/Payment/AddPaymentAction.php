<?php

namespace App\Modules\Billing\Actions\Payment;

use App\Modules\Billing\Models\BillingPaymentModel;
use App\Modules\Billing\Repositories\PaymentRepository;
use App\Modules\Rate\Models\RateGroupModel;
use App\Modules\Stead\Models\SteadModel;
use Auth;
use Illuminate\Support\Facades\Cache;

/**
 * обновить данные платежа
 *
 */
class AddPaymentAction
{

    private $payment;

    public function __construct()
    {
        $this->payment = new BillingPaymentModel();
    }


    public function stead($steadId)
    {
        if ($steadId) {
            $this->payment->stead_id = $steadId;
        }
        return $this;
    }

    public function paymentDate($date)
    {
        if ($date) {
            $this->payment->payment_date = $date;
        }
        return $this;
    }

    public function price($price)
    {
        if ($price) {
            $this->payment->price = str_replace(',', '.', $price);;
        }
        return $this;
    }

    public function rateGroup($rate_group)
    {
        if ($rate_group) {
            $this->payment->rate_group_id = $rate_group;
        }
        return $this;
    }

    public function parseRawData(array $raw)
    {
        $payment_date = $raw[0];
        $this->payment->price = str_replace(',', '.', $raw[1]);
        $this->payment->payment_date = $payment_date;
        $this->payment->payment_type = 1;
        $this->payment->raw_data = $raw;
        $this->payment->user_id = Auth::user()->id ?? null;
        $this->parseStead();
        $this->parseType();
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function run(): BillingPaymentModel
    {
        $this->checkNoDublicate();
        if ($this->payment->duplicate || $this->payment->logAndSave('Создание платежа')) {
            Cache::tags(['payment'])->flush();
            return $this->payment;
        }
        return throw  new \Exception('Ошибка создания платежа');
    }

    private function parseStead($col = 2)
    {
        try {
            $data = $this->payment->raw_data;
            $str = mb_strtolower($data[$col]);
            if ($str) {
                $str = str_replace('-', '/', $str);
                $str = str_replace('-', '/', $str);
                $str = str_replace(',', '/', $str);
                $str = str_replace('участок', '', $str);
                $str = str_replace('№', '', $str);
                $str = str_replace(' ', '', $str);
                $str = preg_replace('/[^0-9\/]/', '', $str);
                $stead = SteadModel::query()->where('number', 'like', "%{$str}%")->first();
                if (!$stead) {
                    //todo переделать в динамику
                    $str = str_replace('Л сч 502 10линия', '502', $data[$col]);
                    $str = str_replace('Л сч502 10линия', '502', $str);
                    $str = str_replace('288, 289', '288', $str);
                    $str = str_replace('526/525', '525/526', $str);
                    $stead = SteadModel::query()->where('number', 'like', "%{$str}%")->first();
                }
                if ($stead) {
                    $this->payment->stead_id = $stead->id;
                }
            } else {
                $str = mb_strtolower($data[4]);
                $c = explode(';', $str);
                if (count($c) == 4) {
                    $stead = SteadModel::query()->where('number', 'like', "%{$c[2]}%")->first();
                    if ($stead) {
                        $this->payment->stead_id = $stead->id;
                    }
                }
                if (!$this->payment->stead_id) {
                    $str = str_replace('№', '', $str);
                    $str = str_replace('участки', '!@!', $str);
                    $str = str_replace('участка', '!@!', $str);
                    $str = str_replace('участок', '!@!', $str);
                    $str = str_replace('уч.', '!@!', $str);
                    $str = str_replace('уч', '!@!', $str);
                    $str = str_replace('лс', '!@!', $str);
                    $c = explode('!@!', $str);
                    if (count($c) > 1) {
                        $text = trim($c[1]);
                        $text = str_replace(',', ' ', $text);
                        $text = str_replace('.', ' ', $text);
                        $text = str_replace(';', ' ', $text);
                        $n = explode(' ', $text);
                        $stead = SteadModel::query()->where('number', 'like', "%{$n[0]}%")->first();
                        if ($stead) {
                            $this->payment->stead_id = $stead->id;
                        }
                    }
                }
            }
            if (!$this->payment->stead_id || $stead->number != mb_strtolower($data[$col])) {
                $this->payment->error = true;
            }
            return $this;
        } catch (\Exception $e) {
            $this->payment->error = true;
            return $this;
        }
    }

    private function parseType($col = 4)
    {
        $data = $this->payment->raw_data;
        $str = mb_strtolower($data[$col]);
        $typeList = RateGroupModel::all();
        $type = false;
        foreach ($typeList as $item) {
            $options = $item->options;
            if (isset($options['tag'])) {
                $tags = $options['tag'];
                foreach ($tags as $tag) {
                    if (stristr($str, $tag) && !$type) {
                        $type = $item->id;
                    }
                }
            }
        }
        if ($type) {
            $this->payment->rate_group_id = $type;
        } else {
            $this->payment->error = true;
        }
        return $this;
    }

    private function checkNoDublicate()
    {
        try {
            $old = (new PaymentRepository())
                ->findByPrice($this->payment->price)
//            ->findByDate($this->payment->payment_date)
                ->forDate($this->payment->payment_date, $this->payment->payment_date)
                ->firstOrFail();
            $this->payment = $old;
            $this->payment->duplicate = true;
        } catch (\Exception $e) {
        }
    }

}
