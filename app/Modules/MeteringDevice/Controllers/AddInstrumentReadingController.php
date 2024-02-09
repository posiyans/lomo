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
use Illuminate\Support\Facades\DB;

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
        try {
            $user = Auth::user();
            $stead_id = '';
            $user_admin = $user->ability('superAdmin', ['stead-show', 'reading-edit']);
            if (!$user_admin) {
                $stead_id = $user->owner->steads->map(function ($item) {
                    return $item->id;
                });
            }
            DB::beginTransaction();
            foreach ($request->devices as $data) {
                $device = (new MeteringDeviceRepository())->forStead($stead_id)->getById($data['device_id']);
                $payment_id = '';
                if ($request->payment_id && $user_admin) {
                    $payment = (new PaymentRepository())->byId($request->payment_id);
                    if ($payment->stead_id != $device->stead_id) {
                        return response(['errors' => 'Платеж и прибор от разных участков'], 450);
                    }
                    $request->date = $payment->payment_date;
                    $payment_id = $payment->id;
                    $reading = (new InstrumentReadingRepository())->for_device($device->id)->for_payment($payment_id)->first();
                    if ($reading) {
                        $reading = (new UpdateInstrumentReadingAction($reading))->value($data['value'])->run();
                        $this->postSave($reading);
                        return response(['status' => true, 'data' => new InstrumentReadingResource($reading)]);
                    }
                } else {
                    $last_reading = $device->last_reading();
                    if (strtotime($last_reading->date) > strtotime($request->date)) {
                        return response(['errors' => 'В системе есть показания на более поздний период'], 450);
                    }
                    if (strtotime($last_reading->date) == strtotime($request->date)) {
                        return response(['errors' => 'Сегодня уже передовали показания'], 450);
                    }
                    if ($last_reading->value >= $data['value']) {
                        return response(['errors' => 'Значение показаний должны быть больше предыдущих (' . $data['value'] . ')'], 450);
                    }
                }
                $reading = (new AddInstrumentReadingAction($device))
                    ->value($data['value'])
                    ->date($request->date)
                    ->payment($payment_id)
                    ->run();
                $this->postSave($reading);
            }
            DB::commit();
            return response(['status' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['errors' => $e->getMessage()], 450);
        }
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
