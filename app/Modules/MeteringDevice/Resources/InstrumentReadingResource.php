<?php

namespace App\Modules\MeteringDevice\Resources;

use App\Modules\Billing\Resources\InvoiceResource;
use App\Modules\Billing\Resources\PaymentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InstrumentReadingResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['device'] = new MeteringDeviceResource($this->device_register);
        $data['invoice'] = $this->invoice ? new InvoiceResource($this->invoice) : null;
        $data['payment'] = $this->payment ? new PaymentResource($this->payment) : null;

        return $data;
    }
}
