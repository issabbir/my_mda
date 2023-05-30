<?php

namespace App\Providers;


use App\Contracts\Cms\ApprovalContract;
use App\Contracts\Cms\CommonContract;
use App\Contracts\Cms\NotificationsContract;
use App\Contracts\Cms\SettingsContract;
use App\Contracts\Cms\ShiftingContract;
use App\Contracts\Cms\VesselContract;
use App\Managers\Cms\ApprovalManager;
use App\Managers\Cms\CommonManager;
use App\Managers\Cms\NotificationsManager;
use App\Managers\Cms\SettingsManager;
use App\Managers\Cms\ShiftingManager;
use App\Managers\Cms\VesselManager;
use Illuminate\Support\ServiceProvider;

class CmsContractServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SettingsContract::class, SettingsManager::class);
        $this->app->bind(NotificationsContract::class, NotificationsManager::class);
        $this->app->bind(ShiftingContract::class,ShiftingManager::class);
        $this->app->bind(VesselContract::class,VesselManager::class);
        $this->app->bind(CommonContract::class,CommonManager::class);
        $this->app->bind(ApprovalContract::class,ApprovalManager::class);
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
