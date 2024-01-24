<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\InstrumentReadings\Classes;

use App\Modules\MeteringDevice\Models\InstrumentReadingModel;


class DeleteInstrumentReadingClass

{

    /**
     * удалить показание прибора
     *
     * @param \App\Modules\MeteringDevice\Models\InstrumentReadingModel $reading
     * @return bool
     * @throws \Exception
     */
    public function delete(InstrumentReadingModel $reading)
    {
        if ($reading->invoice_id) {
            throw new \Exception('Нельзя удалить показания по которому выставлен счет');
        }
        if (!$reading->delete()) {
            throw new \Exception('Ошибка удаления');
        }
        return true;
    }


}
