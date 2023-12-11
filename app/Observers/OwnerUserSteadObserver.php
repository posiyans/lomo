<?php

namespace App\Observers;

use App\Modules\Owner\Models\OwnerUserSteadModel;
use Illuminate\Support\Facades\Cache;

class OwnerUserSteadObserver
{
    /**
     * Handle the OwnerUserSteadModel "created" event.
     *
     * @param  \App\Modules\Owner\Models\OwnerUserSteadMode  $ownerUserSteadModel
     * @return void
     */
    public function created(OwnerUserSteadModel $ownerUserSteadModel)
    {
        $this->clearCache();
    }

    /**
     * Handle the OwnerUserSteadModel "updated" event.
     *
     * @param  \App\Modules\Owner\Models\OwnerUserSteadMode  $ownerUserSteadModel
     * @return void
     */
    public function updated(OwnerUserSteadModel $ownerUserSteadModel)
    {
        $this->clearCache();
    }

    /**
     * Handle the OwnerUserSteadModel "deleted" event.
     *
     * @param  \App\Modules\Owner\Models\OwnerUserSteadMode  $ownerUserSteadModel
     * @return void
     */
    public function deleted(OwnerUserSteadModel $ownerUserSteadModel)
    {
        $this->clearCache();
    }

    /**
     * Handle the OwnerUserSteadModel "restored" event.
     *
     * @param  \App\Modules\Owner\Models\OwnerUserSteadMode  $ownerUserSteadModel
     * @return void
     */
    public function restored(OwnerUserSteadModel $ownerUserSteadModel)
    {
        $this->clearCache();
    }

    /**
     * Handle the OwnerUserSteadModel "force deleted" event.
     *
     * @param  \App\Modules\Owner\Models\OwnerUserSteadMode  $ownerUserSteadModel
     * @return void
     */
    public function forceDeleted(OwnerUserSteadModel $ownerUserSteadModel)
    {
        $this->clearCache();
    }

    private function clearCache()
    {
        Cache::tags(['owner_user'])->flush();
    }
}
