<?php

namespace App\Modules\MeteringDevice\Resources;

use App\Modules\MeteringDevice\Repositories\PreviousReadingsModelRepository;
use App\Modules\Rate\Repositories\RateRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class InstrumentReadingSmallResource extends JsonResource
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
        $data['previous_value'] = $this->reading->options['previous_reading'] ?? (new PreviousReadingsModelRepository($this->resource))->value();
        $data['delta'] = $this->reading->options['calculated_value'] ?? (new PreviousReadingsModelRepository($this->resource))->delta();
        $data['rate'] = $this->reading->options['rate'] ?? RateRepository::for_instrument_reading($this->resource);
        $data['cost'] = round($this->reading->options['cost'] ?? $data['delta'] * $data['rate']->ratio_a, 2);
        return $data;
    }
}
