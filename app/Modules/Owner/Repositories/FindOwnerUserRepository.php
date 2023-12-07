<?php

namespace App\Modules\Owner\Repositories;

use App\Modules\Owner\Models\OwnerUserModel;
use App\Modules\Stead\Repositories\SteadRepository;
use Illuminate\Support\Facades\Cache;

class FindOwnerUserRepository
{

    private $find;
    private $allOwner;
    private $data;

    public function __construct($find)
    {
        $this->find = $find;
        $this->data = collect();
        $this->getAllOwner();
    }


    public function uids()
    {
        $this->findFio();
        $this->findStead();
        return $this->data->map(function ($item) {
            return $item->uid;
        })->unique();
    }

    /**
     * получить всев собственников в память
     *
     * @return void
     */
    public function getAllOwner()
    {
`        $this->allOwner = Cache::tags('owner_user')->remember('allOwnerList', 6000, function () {
            $data = [];
            $owners = OwnerUserModel::all();
            foreach ($owners as $owner) {
                $data[$owner->fullName()] = $owner;
            }
            ksort($data);
            return $data;
        });`
    }

    /**
     * посик по фио
     *
     * @param $find
     */
    public function findFio()
    {
        $find = explode(' ', mb_strtolower(str_replace('  ', ' ', trim($this->find))));
        foreach ($this->allOwner as $key => $value) {
            $f = true;
            foreach ($find as $item) {
                if (!stristr(mb_strtolower($key), $item)) {
                    $f = false;
                }
            }
            if ($f) {
                $this->data->push($value);
            }
        }
    }

    /**
     * поиск по номеру участка
     *
     * @return void
     */
    public function findStead()
    {
        $steads = (new SteadRepository())->findByNumber($this->find)->run();
        foreach ($steads as $stead) {
            foreach ($stead->owners as $owner) {
                $this->data->push($owner);
            }
        }
    }


}