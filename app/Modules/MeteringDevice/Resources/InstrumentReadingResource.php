<?php

namespace App\Modules\MeteringDevice\Resources;

use App\Modules\Billing\Resources\InvoiceResource;
use App\Modules\Billing\Resources\PaymentResource;
use App\Modules\MeteringDevice\Repositories\PreviousReadingsModelRepository;
use App\Modules\Rate\Repositories\RateRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class InstrumentReadingResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['device'] = new MeteringDeviceResource($this->metering_device);
        $data['invoice'] = $this->invoice ? new InvoiceResource($this->invoice) : null;
        $data['payment'] = $this->payment ? new PaymentResource($this->payment) : null;
        $data['previous_value'] = $this->reading->options['previous_reading'] ?? (new PreviousReadingsModelRepository($this->resource))->value();
        $data['delta'] = $this->reading->options['calculated_value'] ?? (new PreviousReadingsModelRepository($this->resource))->delta();
        $data['rate'] = $this->reading->options['rate'] ?? (new RateRepository())->for_instrument_reading($this->resource);;
        $data['cost'] = $this->reading->options['cost'] ?? $data['delta'] * $data['rate']->ratio_a;
        return $data;
    }
}
