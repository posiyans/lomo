<?php

namespace App\Modules\Billing\Resources;

use App\Modules\Billing\Repositories\BalanceRepository;
use App\Modules\Billing\Repositories\PaymentRepository;
use App\Modules\Rate\Repositories\RateGroupRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class BalanceForSteadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'stead' => [
                'id' => $this->id,
                'number' => $this->number,
                'size' => $this->size,
            ]
        ];
        $balance = (new BalanceRepository())->forStead($this->id)->getPrice();
        $rate_groups = (new RateGroupRepository())->get();
        $rates = [];
        foreach ($rate_groups as $group) {
            $rates[] = [
                'id' => $group->id,
                'name' => $group->name,
                'price' => (new BalanceRepository())
                    ->forStead($this->id)
                    ->forRateGroupId($group->id)
                    ->getPrice(),
            ];
        }
        $data['price'] = $balance;
        $data['rates'] = $rates;
        $data['last_pay'] = null;
        $last_pay = (new PaymentRepository())->forStead($this->id)->paginate(1);
        if (count($last_pay) == 1) {
            $data['last_pay'] = $last_pay[0];
        }
        return $data;
    }
}
