<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\InstrumentReadings;

use App\Http\Controllers\Admin\AbstractAdminController;
use App\Http\Resources\Admin\Bookkeeping\AdminInstrumentReadingsResource;
use App\Models\Stead;
use App\Modules\MeteringDevice\Models\InstrumentReadingModel;
use App\Modules\MeteringDevice\Models\MeteringDevice;
use Illuminate\Http\Request;


class ReadingForSteadController extends AbstractAdminController

{

    /**
     * получить список показаний по приборам для участка
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Stead $stead, Request $request)
    {
        $query = InstrumentReadingModel::where('stead_id', $stead->id);
        if ($request->type_id || $request->primaryType) {
            $types_id = MeteringDevice::getDeviceIdForStead($request->primaryType, $stead->id, $request->type_id);
            $query->whereIn('device_id', $types_id);
        }
        $items = $query->orderBy('created_at', 'desc')
            ->paginate($request->limit);
        return AdminInstrumentReadingsResource::collection($items);
    }


}
