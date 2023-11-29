<?php

namespace App\Modules\Owner\Actions;

use App\Modules\Owner\Models\OwnerUserModel;
use App\Modules\Owner\Repositories\OwnerFieldRepository;
use Illuminate\Support\Str;

/**
 * Создать собственника
 */
class CreateOwnerAction
{
    protected $owner;
    protected $data;
    public $error = false;
    public $error_message = '';

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * @return OwnerUserModel|void
     * @throws \Exception
     */
    public function run()
    {
        if (count($this->data) > 0) {
            $this->owner = new OwnerUserModel();
            $this->owner->uid = Str::uuid();
            if (!$this->owner->logAndSave('Добавление собственника')) {
                return throw new \Exception('Ошибка добавления собственника');
            }
            $this->addValues();
            return $this->owner;
        }
    }

    private function addValues()
    {
        foreach ((new OwnerFieldRepository())->keys() as $field) {
            if (isset($this->data[$field])) {
                $this->owner->setValue($field, $this->data[$field]);
            }
        }
    }


}
