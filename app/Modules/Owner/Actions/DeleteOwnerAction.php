<?php

namespace App\Modules\Owner\Actions;

use App\Modules\Owner\Models\OwnerUserModel;
use Illuminate\Support\Facades\DB;

/**
 * Удалить собственника
 */
class DeleteOwnerAction
{
    private $owner;
    protected $data;
    public $error = false;
    public $error_message = '';

    public function __construct(OwnerUserModel $owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function run()
    {
        DB::beginTransaction();
        $this->deleteUserFields();
        $this->deleteSteadInfo();
        if ($this->owner->logAndDelete('Удалние собственника')) {
            DB::commit();
            return true;
        }
        DB::rollBack();
        throw new \Exception('Ошибка удаления');
    }

    private function deleteSteadInfo()
    {
        $this->owner->steads()->detach();
    }

    private function deleteUserFields()
    {
        $fields = $this->owner->values;
        foreach ($fields as $field) {
            $field->logAndDelete('Удалние собственника');
        }
    }


}
