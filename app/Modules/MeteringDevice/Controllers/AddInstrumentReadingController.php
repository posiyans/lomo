<?php

namespace App\Modules\MeteringDevice\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Repositories\PaymentRepository;
use App\Modules\MeteringDevice\Actions\AddInstrumentReadingAction;
use App\Modules\MeteringDevice\Actions\CheckDataInInstrumentReadingAction;
use App\Modules\MeteringDevice\Actions\UpdateInstrumentReadingAction;
use App\Modules\MeteringDevice\Repositories\InstrumentReadingRepository;
use App\Modules\MeteringDevice\Repositories\MeteringDeviceRepository;
use App\Modules\MeteringDevice\Resources\InstrumentReadingResource;
use App\Modules\MeteringDevice\Validators\AddInstrumentReadingValidator;
use Illuminate\Support\Facades\Auth;

/**
 * Добавить показания приборов
 */
class AddInstrumentReadingController extends Controller
{

    // 'instrument_reading-edit'
    public function __construct()
    {
        $this->middleware('ability:superAdmin|owner,stead-show');
    }

    public function __invoke(AddInstrumentReadingValidator $request)
    {
//        try {
        $user = Auth::user();
        $stead_id = '';
        if (!$user->ability('superAdmin', 'stead-show')) {
            $stead_id = $user->owner->steads->map(function ($item) {
                return $item->id;
            });
        }
        $device = (new MeteringDeviceRepository())->forStead($stead_id)->getById($request->device_id);
        $payment_id = '';
        if ($request->payment_id && $user->ability('superAdmin', 'stead-show')) {
            $payment = (new PaymentRepository())->byId($request->payment_id);
            if ($payment->stead_id != $device->stead_id) {
                return response(['errors' => 'Платеж и прибор от разных участков'], 450);
            }
            $request->date = $payment->payment_date;
            $payment_id = $payment->id;
            $reading = (new InstrumentReadingRepository())->for_device($device->id)->for_payment($payment_id)->first();
            if ($reading) {
                $reading = (new UpdateInstrumentReadingAction($reading))->value($request->value)->run();
                $this->postSave($reading);
                return response(['status' => true, 'data' => new InstrumentReadingResource($reading)]);
            }
        } else {
            $last_reading = $device->last_reading();
            if (strtotime($last_reading->date) >= strtotime($request->date)) {
                return response(['errors' => 'В системе есть показания на более поздний период'], 450);
            }
            if ($last_reading->value >= $request->value) {
                return response(['errors' => 'Значение показаний должны быть больше предыдущих'], 450);
            }
        }
        $reading = (new AddInstrumentReadingAction($device))
            ->value($request->value)
            ->date($request->date)
            ->payment($payment_id)
            ->run();
        $this->postSave($reading);
        return response(['status' => true, 'data' => new InstrumentReadingResource($reading)]);
//        } catch (\Exception $e) {
//            return response(['errors' => $e->getMessage()], 450);
//        }
    }

    /**
     * действия после сохранения показяний
     * @param $reading
     * @return void
     * @throws \Exception
     */
    private function postSave($reading)
    {
//        (new FillOptionsInstrumentReadingAction($reading))->run();
        (new CheckDataInInstrumentReadingAction())
            ->after_reading($reading)
            ->run();
    }

}
