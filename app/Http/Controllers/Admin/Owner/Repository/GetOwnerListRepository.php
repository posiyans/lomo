<?php

namespace App\Http\Controllers\Admin\Owner\Repository;

use App\Http\Controllers\Controller;
use App\Models\Owner\OwnerUserModel;
use App\Models\Stead;
use Illuminate\Support\Facades\Cache;


class GetOwnerListRepository extends Controller
{
    public $status = true;
    public $error = false;
    public $rezult;
    public $total;
    public $offset;
    protected $allOwner;
    protected $data;

    public function __construct($find, $all = false)
    {
        $this->getAllOwner();
        $this->findOwner($find);
        $this->total = count($this->data);
        if ($all) {
            $this->rezult = array_values($this->data);
        } else {
            $this->rezult = $this->paginate(array_values($this->data));
        }
    }


    /**
     * получить всех собственников
     *
     */
    public function getAllOwner()
    {
        $this->allOwner = Cache::tags('owner_user')->remember('allOwnerList', 6000, function () {
            $data = [];
            $owners = OwnerUserModel::all();
            foreach ($owners as $owner) {
                $data[$owner->fullName()] = $owner;
            }
            ksort($data);
            return $data;
        });
    }

    /**
     *сменить сортировку при поиске по участкам
     */
    public function sortByStead()
    {
        $data = [];
        foreach ($this->allOwner as $owner) {
                if (isset($owner->steads[0])) {
                    $data[$owner->steads[0]->id] = $owner;
                }
        }
        ksort($data);
        $this->allOwner = $data;
    }

    /**
     * посик по фио
     *
     * @param $find
     */
    public function findFio($find)
    {
        if ($find) {
            //$find = preg_replace('/[0-9]/', '', $find);
            $find = explode(' ', mb_strtolower(str_replace('  ', ' ', trim($find))));
            $data = [];
            foreach ($this->allOwner as $key => $value) {
                $f = true;
                foreach ($find as $item) {
                    if (!stristr(mb_strtolower($key), $item)) {
                        $f = false;
                    }
                }
                if ($f) {
                    $data[$key] = $value;
                }
            }
            $this->data = $data;
        } else {
           $this->data = $this->allOwner;
        }
    }

    /**
     * поиск по номеру участка
     *
     * @param $find
     */
    private function findStead($find)
    {
        preg_match("/[\d]+/", $find,$match);
        if (isset($match[0]) && strlen($match[0]) > 0) {
            $steads_id = Stead::where('number','LIKE', '%'.$find.'%')->pluck('id')->toArray();
            $data = [];
            foreach ($this->allOwner as $key => $value) {
                $f = false;
                if ($value->steads) {
                    foreach ($value->steads as $item) {
                        if (in_array($item->stead_id, $steads_id)) {
                            $f = true;
                        }
                    }
                }
                if ($f) {
                    $data[$key] = $value;
                }
            }
            $this->data = $data;
        }
    }

    /**
     * определяем что ищем и запускам нужный метод
     * по фио или по участку
     * @param $find
     */
    private function findOwner($find)
    {
        preg_match("/[\d]+/", $find,$match);
        if (isset($match[0]) && strlen($match[0]) > 0) {
            $this->sortByStead();
            $this->findStead($find);
        } else {
            $this->findFio($find);
        }
    }
}

