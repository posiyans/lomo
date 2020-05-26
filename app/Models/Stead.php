<?php
namespace App\Models;

use App\MyModel;
use App\Models\InstrumentReadings;
use App\User;
use Illuminate\Support\Facades\Cookie;


/**
 * Модель участков
 */
class Stead extends MyModel
{
    protected $fillable = ['number', 'discriptions'];
    protected $casts = [
        'discriptions' => 'array',
    ];
    //

    public $userFullName = '';
    /**
     * отношение с пользователем
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function userFullName()
    {
        if ($this->user){
            $this->userFullName = $this->user->fullName();
            return $this->userFullName;
        }
        return '';
    }

    public function indications()
    {
        return $this->hasMany(InstrumentReadings::class, 'steads_id', 'id');
    }

    /**
     * отношение с садоводством
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function gardient()
    {
        return $this->hasOne(Gardening::class, 'id', 'gardening_id');
    }

    public function MeteringDevice()
    {
        return $this->hasMany(MeteringDevice::class, 'steads_id', 'id');
    }

    public function getIndication($n=false) {
        $devices = MeteringDevice::where('enable', 1)->get();
        $ind= [];
        foreach ($devices as $device){
            $indications = InstrumentReadings::where('type_id', $device->id)
                ->where('stead_id', $this->id)
                ->orderBy('created_at', 'desc')
                ->limit(2)
                ->get();
            $device->val_new = isset($indications[0]->value) ? $indications[0]->value : 0;
            $device->val_old = isset($indications[1]->value) ? $indications[1]->value : 0;
            $device->value = $device->val_new - $device->val_old;
            $device->rateNow();
            $device->cash = $device->value * $device->rate->ratio_a  + $device->rate->ratio_b;
            $ind[$device->id] = $device;
        }
        $this->Indications = $ind;
    }


    /**
     * Сохрание данных об участке
     * @param $request
     */
    public function saveData($request){
        $this->surname  = isset($request->surname)  ? $request->surname : $this->surname ;
        $this->name = isset($request->name) ? $request->name: $this->name;
        $this->patronymic = isset($request->patronymic) ? $request->patronymic: $this->patronymic;
        $this->size = isset($request->size) ? $request->size: $this->size;
        $this->email = isset($request->email) ? $request->email: $this->email;
        $this->save();
    }


    public function setSession($request){

        session(['stead_id' => $this->id]);
        isset($request->surname) ? (['surname' =>  $request->surname]):'';
        isset($request->name) ? session(['name' => $request->name]):'';
        isset($request->patronymic) ? session(['patronymic' => $request->patronymic]):'';
        isset($request->size) ? session(['stead_size' => $request->size]):'';
    }


    /**
     * добавить примечание к участку
     *
     * @param $id -- id участка
     * @param $note -- примечание
     * @return bool
     */
    public static function addNote($id, $note){
        $stead = Stead::find($id);
        if ($stead){

//            $stead_clone = clone $stead;
            $discriptions = $stead->discriptions;
            if (isset($discriptions['note'])){
                $discriptions['note'] .= "\n".$note;
            } else {
                $discriptions['note'] = $note;
            }
            $stead->discriptions = $discriptions;
            if ($stead->save()){

                return $stead;
            }
        }
        return false;
    }

    /**
     * обноиьт данные участка
     * @param $id
     * @param $model
     * @return bool
     */
    public static function updateStead($id, $model)
    {
       $stead = Stead::find($id);
            if ($stead && $model['id'] == $stead->id){
                $stead_clone = clone $stead;
                $stead->number =  $model['number'];
                $stead->size =  $model['size'];
                $stead->discriptions =  $model['discriptions'];
                if ($stead->save()){
                    Log::saveDiff($stead, $stead_clone, 'Изменение данныз об участке');
                    return $stead;
                }
            }
        return false;
    }
}
