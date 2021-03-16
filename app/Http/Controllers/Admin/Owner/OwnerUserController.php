<?php

namespace App\Http\Controllers\Admin\Owner;

use App\Http\Controllers\Controller;
use App\Models\Owner\OwnerUserModel;
use App\Models\Owner\OwnerUserSteadModel;
use Illuminate\Http\Request;
use App\Models\Stead;
use Illuminate\Support\Facades\DB;

/*
 * добавить участок собственику
 *
 */
class OwnerUserController extends Controller
{

    protected $data;
    protected $error;
    protected $er_code;

    /**
     * добавить участок собственнику
     *
     * @param Request $request
     * @return array
     */
    public function add(Request $request)
    {
        $stead_id = $request->post('stead', false);
        $owner_id = $request->post('owner', false);
        $proportion = $request->post('proportion', 100);
        $oldOwnerStead = $request->post('allOwner', []);

        try {
            $this->checkDubliсate($stead_id, $owner_id);
            DB::beginTransaction();

            if (is_array($oldOwnerStead)) {
                $this->setOldProportion($oldOwnerStead);
            }


            if ($stead_id && $owner_id && $proportion) {
                $stead = Stead::find($stead_id);
                if ($stead) {
                    if ($this->checkUniqUserStead($stead_id, $proportion, $oldOwnerStead)) {
                        if (($owner = OwnerUserModel::find($owner_id))) {
                            $userStead = new  OwnerUserSteadModel();
                            $userStead->owner_uid = $owner->uid;
                            $userStead->stead_id = $stead->id;
                            $userStead->proportion = $proportion;
                            if ($userStead->logAndSave('Добавлен участок собственнику')) {
                                DB::commit();
                                return ['status' => true, 'data' => $userStead];
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'error' => $this->error, 'code'=> $this->er_code, 'data' => $this->data];
        }
    }

    /**
     * проверка суммы долей на привышение 100%
     *
     * @param $stead_id
     * @param $proportion
     * @param array $old
     * @return bool
     * @throws \Exception
     */
    public function checkUniqUserStead($stead_id, $proportion, $old=[])
    {
        $oldData = [];
        foreach ($old as $i) {
            $oldData[$i['id']] = $i['proportion'];
        }
        $userSteads = OwnerUserSteadModel::where('stead_id', $stead_id)->get();
        foreach ($userSteads as $item) {
            if (isset($oldData[$item->id])) {
                $item->proportion = $oldData[$item->id];
                $item->logAndSave('Смена доли владения');
            } else {
                $proportion +=  $item->proportion;
            }
        }
        if ($proportion <= 100) {
            return true;
        }
        $this->data = $userSteads;
        $this->error = 'Общая доля не может превышать 100%';
        $this->er_code = 100;
        throw new \Exception();
    }

    /**
     * установкаа долей у других собственников
     *
     * @param array $owners
     */
    public function setOldProportion(array $owners)
    {
        foreach ($owners as $item) {
            if ($owner = OwnerUserSteadModel::find($item['id'])) {
                $owner->proportion = $item['proportion'];
                $owner->logAndSave('Смена доли собстванности');
            }
        }
    }

    /**
     * проверить на наличие уже этого участка у собственника
     *
     * @param $stead_id
     * @param $owner_id
     * @return bool
     * @throws \Exception
     */
    public function checkDubliсate($stead_id, $owner_id)
    {
        if ($owner = OwnerUserModel::find($owner_id)) {
            $check = OwnerUserSteadModel::where('owner_uid', $owner->uid)->where('stead_id', $stead_id)->first();
            if ($check) {
                $this->error = 'У собственника уже есть этот участок';
                throw new \Exception();
            } else {
                return true;
            }
        }
        $this->error = 'Собственник не найден';
        throw new \Exception();
    }
}

