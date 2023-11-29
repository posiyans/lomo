<?php

namespace App\Modules\Owner\Actions;

use App\Models\Owner\OwnerUserModel;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;


class CreateOwnerAction
{
    protected $owner;
    protected $data;
    public $error = false;
    public $error_message = '';

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct($data = [])
    {
        if (count($data) > 0) {
            DB::beginTransaction();
            $this->data = $data;
            $this->owner = new OwnerUserModel();
            $this->owner->uid = Uuid::uuid4()->toString();
            if ($this->owner->logAndSave('Добавили собственника')) {
                $this->addValues();
                if (!$this->error) {
                    DB::commit();
                }
            }
            DB::rollBack();
        }
    }

    public function getOwnerId()
    {
        return $this->owner->id;
    }

    public function addValues()
    {
        foreach (array_keys($this->owner->fields) as $field) {
            if (isset($this->data[$field])) {
                if (!$this->owner->setValue($field, $this->data[$field])) {
                    $this->error = true;
                    $this->error_message = 'Ошибка добавления поля';
                }
            }
        }
    }

}
