<?php

namespace Tests\Helpers;

use Illuminate\Support\Facades\Log;

class DummyModelObserver
{
    /**
     * Handle the DummyModel "created" event.
     */
    public function created(DummyModel $DummyModel): void
    {
        Log::info('DummyModel Created');
    }

    /**
     * Handle the DummyModel "updated" event.
     */
    public function updated(DummyModel $DummyModel): void
    {
        Log::info('DummyModel Updated');
    }

    /**
     * Handle the DummyModel "deleted" event.
     */
    public function deleted(DummyModel $DummyModel): void
    {
        Log::info('DummyModel Deleted');
    }

    /**
     * Handle the DummyModel "restored" event.
     */
    public function restored(DummyModel $DummyModel): void
    {
        Log::info('DummyModel Restored');
    }

    /**
     * Handle the DummyModel "force deleted" event.
     */
    public function forceDeleted(DummyModel $DummyModel): void
    {
        Log::info('DummyModel ForceDeleted');
    }
}
