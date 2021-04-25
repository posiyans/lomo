<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\InstrumentReadings\Classes;

use App\Models\Receipt\InstrumentReadings;


class DeleteInstrumentReadingClass

{

    /**
     * удалить показание прибора
     *
     * @param InstrumentReadings $reading
     * @return bool
     * @throws \Exception
     */
    public function delete(InstrumentReadings $reading)
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
