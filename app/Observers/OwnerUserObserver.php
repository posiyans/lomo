<?php

namespace App\Observers;

use App\Modules\Owner\Models\OwnerUserModel;
use Illuminate\Support\Facades\Cache;

class OwnerUserObserver
{
    /**
     * Handle the OwnerUserModel "created" event.
     *
     * @param  \App\Modules\Owner\Models\OwnerUserModel  $ownerUserModel
     * @return void
     */
    public function created(OwnerUserModel $ownerUserModel)
    {
        $this->clearCache();
    }

    /**
     * Handle the OwnerUserModel "updated" event.
     *
     * @param  \App\Modules\Owner\Models\OwnerUserMode  $ownerUserModel
     * @return void
     */
    public function updated(OwnerUserModel $ownerUserModel)
    {
        $this->clearCache();
    }

    /**
     * Handle the OwnerUserModel "deleted" event.
     *
     * @param  \App\Modules\Owner\Models\OwnerUserMode  $ownerUserModel
     * @return void
     */
    public function deleted(OwnerUserModel $ownerUserModel)
    {
        $this->clearCache();
    }

    /**
     * Handle the OwnerUserModel "restored" event.
     *
     * @param  \App\Modules\Owner\Models\OwnerUserMode  $ownerUserModel
     * @return void
     */
    public function restored(OwnerUserModel $ownerUserModel)
    {
        $this->clearCache();
    }

    /**
     * Handle the OwnerUserModel "force deleted" event.
     *
     * @param  \App\Modules\Owner\Models\OwnerUserMode  $ownerUserModel
     * @return void
     */
    public function forceDeleted(OwnerUserModel $ownerUserModel)
    {
        $this->clearCache();
    }


    private function clearCache()
    {
        Cache::tags(['owner_user'])->flush();
    }
}
