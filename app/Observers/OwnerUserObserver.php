<?php

namespace App\Observers;

use App\Models\Owner\OwnerUserModel;
use Illuminate\Support\Facades\Cache;

class OwnerUserObserver
{
    /**
     * Handle the OwnerUserModel "created" event.
     *
     * @param  \App\Models\Owner\OwnerUserModel  $ownerUserModel
     * @return void
     */
    public function created(OwnerUserModel $ownerUserModel)
    {
        $this->clearCache();
    }

    /**
     * Handle the OwnerUserModel "updated" event.
     *
     * @param  \App\Models\Owner\OwnerUserModel  $ownerUserModel
     * @return void
     */
    public function updated(OwnerUserModel $ownerUserModel)
    {
        $this->clearCache();
    }

    /**
     * Handle the OwnerUserModel "deleted" event.
     *
     * @param  \App\Models\Owner\OwnerUserModel  $ownerUserModel
     * @return void
     */
    public function deleted(OwnerUserModel $ownerUserModel)
    {
        $this->clearCache();
    }

    /**
     * Handle the OwnerUserModel "restored" event.
     *
     * @param  \App\Models\Owner\OwnerUserModel  $ownerUserModel
     * @return void
     */
    public function restored(OwnerUserModel $ownerUserModel)
    {
        $this->clearCache();
    }

    /**
     * Handle the OwnerUserModel "force deleted" event.
     *
     * @param  \App\Models\Owner\OwnerUserModel  $ownerUserModel
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
