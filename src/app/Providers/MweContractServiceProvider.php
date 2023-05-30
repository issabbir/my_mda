<?php

namespace App\Providers;


use App\Contracts\MessageContract;
use App\Contracts\Mwe\MaintenanceReqContract;
use App\Contracts\Mwe\NotificationsContract;
use App\Contracts\Mwe\SettingsContract;
use App\Contracts\Mwe\WorkshopContract;
use App\Managers\MessageManager;
use App\Managers\Mwe\MaintenanceReqManager;
use App\Managers\Mwe\NotificationsManager;
use App\Managers\Mwe\SettingsManager;
use App\Managers\Mwe\WorkshopManager;
use Illuminate\Support\ServiceProvider;

class MweContractServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SettingsContract::class, SettingsManager::class);
        $this->app->bind(MaintenanceReqContract::class, MaintenanceReqManager::class);
        $this->app->bind(WorkshopContract::class, WorkshopManager::class);
        $this->app->bind(NotificationsContract::class, NotificationsManager::class);
        $this->app->bind(MessageContract::class, MessageManager::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
