<?php

namespace App\Modules\MeteringDevice\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MeteringDevice\Actions\DeleteInstrumentReadingAction;
use App\Modules\MeteringDevice\Models\InstrumentReadingModel;

/**
 * Удалить показания приборов
 */
class DeleteInstrumentReadingController extends Controller
{

    // 'instrument_reading-edit'
    public function __construct()
    {
        $this->middleware('ability:superAdmin,instrument_reading-edit');
    }

    public function __invoke(InstrumentReadingModel $reading)
    {
        try {
            (new DeleteInstrumentReadingAction($reading))->run();
            return response(['status' => true]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
