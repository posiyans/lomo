<?php
namespace App\Models\Receipt;

use App\MyModel;

/**
 * Модель для показаний
 */
class InstrumentReadings extends MyModel
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stead_id', 'payment_id', 'device_id'
    ];

    public function deviceRegister()
    {
        return $this->hasOne(DeviceRegisterModel::class, 'id', 'device_id');
    }

    public function deviceTypeName()
    {
        return $this->deviceRegister->getTypeName();
    }

    public function getPrice()
    {
        $price = Rate::query()
            ->where('device_id', $this->deviceRegister->type_id)
            ->where('created_at', '<', $this->created_at)
            ->orderBy('created_at', 'desc')
            ->first();
        return $price->ratio_a;
    }

    /**
     * получить придыдущее показание
     *
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public function getPreviousReadings()
    {
        $item = self::query()
            ->where('device_id', $this->device_id)
            ->where('created_at', '<=', $this->created_at)
            ->where('id', '<', $this->id)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($item) {
            return $item->value;
        }
        $item = DeviceRegisterModel::find($this->device_id);
        return $item->initial_data;
    }



    public function checkForLatestData()
    {
        $item = self::query()
            ->where('device_id', $this->device_id)
            ->where('value', '>', $this->value)
            ->first();
        if ($item) {
            return false;
        }
        return true;
    }


}
