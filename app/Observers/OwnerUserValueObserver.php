<?php

namespace App\Observers;

use App\Models\Owner\OwnerUserValueModel;
use Illuminate\Support\Facades\Cache;

class OwnerUserValueObserver
{
    /**
     * Handle the OwnerUserValueModel "created" event.
     *
     * @param  \App\Models\Owner\OwnerUserValueModel  $ownerUserValueModel
     * @return void
     */
    public function created(OwnerUserValueModel $ownerUserValueModel)
    {
        $this->clearCache();
    }

    /**
     * Handle the OwnerUserValueModel "updated" event.
     *
     * @param  \App\Models\Owner\OwnerUserValueModel  $ownerUserValueModel
     * @return void
     */
    public function updated(OwnerUserValueModel $ownerUserValueModel)
    {
        $this->clearCache();
    }

    /**
     * Handle the OwnerUserValueModel "deleted" event.
     *
     * @param  \App\Models\Owner\OwnerUserValueModel  $ownerUserValueModel
     * @return void
     */
    public function deleted(OwnerUserValueModel $ownerUserValueModel)
    {
        $this->clearCache();
    }

    /**
     * Handle the OwnerUserValueModel "restored" event.
     *
     * @param  \App\Models\Owner\OwnerUserValueModel  $ownerUserValueModel
     * @return void
     */
    public function restored(OwnerUserValueModel $ownerUserValueModel)
    {
        $this->clearCache();
    }

    /**
     * Handle the OwnerUserValueModel "force deleted" event.
     *
     * @param  \App\Models\Owner\OwnerUserValueModel  $ownerUserValueModel
     * @return void
     */
    public function forceDeleted(OwnerUserValueModel $ownerUserValueModel)
    {
        $this->clearCache();
    }

    private function clearCache()
    {
        Cache::tags(['owner_user_fields'])->flush();
    }

}
