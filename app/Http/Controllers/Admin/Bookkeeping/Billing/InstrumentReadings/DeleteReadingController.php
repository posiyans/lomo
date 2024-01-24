<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\InstrumentReadings;

use App\Http\Controllers\Admin\AbstractAdminController;
use App\Http\Controllers\Admin\Bookkeeping\Billing\InstrumentReadings\Classes\DeleteInstrumentReadingClass;
use App\Modules\MeteringDevice\Models\InstrumentReadingModel;
use Illuminate\Http\Request;


class DeleteReadingController extends AbstractAdminController

{

    /**
     * получить список показаний по приборам для участка
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(InstrumentReadingModel $reading, Request $request)
    {
        try {
            if ((new DeleteInstrumentReadingClass())->delete($reading)) {
                return ['status' => true];
            }
        } catch (\Exception $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
        return ['status' => false];
    }


}
