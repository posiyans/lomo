<?php

namespace App\Modules\Owner\Actions;

use App\Modules\Owner\Models\OwnerUserModel;
use App\Modules\User\Actions\ChangeRoleToUserAction;
use Illuminate\Support\Facades\DB;

/**
 * Удалить собственника
 */
class DeleteOwnerAction
{
    private $owner;

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
        $this->checkUser();
        // todo проверить на наличие пользователя и снять с него роль собственника!!!!
        if ($this->owner->logAndDelete('Удалние собственника')) {
            DB::commit();
            return true;
        }
        DB::rollBack();
        throw new \Exception('Ошибка удаления');
    }

    /**
     * открепляем участки от собственника
     *
     * @return void
     */
    private function deleteSteadInfo()
    {
        $this->owner->steads()->detach();
    }

    /**
     * Удаляем данные собственника
     *
     * @return void
     */
    private function deleteUserFields()
    {
        $fields = $this->owner->values;
        foreach ($fields as $field) {
            $field->logAndDelete('Удалние собственника');
        }
    }

    /**
     * Удалить роль собственника у пользователя при удалении собственника
     *
     * @return void
     */
    private function checkUser()
    {
        $user = $this->owner->user;
        if ($user) {
            (new ChangeRoleToUserAction($user))->deleteRole('owner');
        }
    }


}
