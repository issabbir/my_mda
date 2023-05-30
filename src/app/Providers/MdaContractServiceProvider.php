<?php

namespace App\Providers;


use App\Contracts\LookupContract;
use App\Contracts\Mda\BsContract;
use App\Contracts\Mda\cashCollectionContract;
use App\Contracts\Mda\CpaVesselsContract;
use App\Contracts\Mda\JettyServiceContract;
use App\Contracts\Mda\MooringChargeContract;
use App\Contracts\Mda\PilotageContract;
use App\Contracts\Mda\SettingsContract;
use App\Contracts\Mda\LocalVesselContract;
use App\Contracts\Mda\SmContract;
use App\Contracts\Mda\TugsRegistrationContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Managers\LookupManager;
use App\Managers\Mda\BsManager;
use App\Managers\Mda\cashcCollectionManager;
use App\Managers\Mda\JettyServiceManager;
use App\Managers\Mda\LocalVesselManager;
use App\Managers\Mda\CpaVesselsManager;
use App\Managers\Mda\MooringChargeManager;
use App\Managers\Mda\PilotageManager;
use App\Managers\Mda\SettingsManager;
use App\Managers\Mda\SmManager;
use App\Managers\Mda\TugsRegistrationManager;
use App\Managers\Pmis\Employee\EmployeeManager;
use Illuminate\Support\ServiceProvider;

class MdaContractServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LookupContract::class, LookupManager::class);
        $this->app->bind(EmployeeContract::class, EmployeeManager::class);
        $this->app->bind(PilotageContract::class, PilotageManager::class);
        $this->app->bind(SettingsContract::class, SettingsManager::class);
        $this->app->bind(LocalVesselContract::class, LocalVesselManager::class);
        $this->app->bind(TugsRegistrationContract::class, TugsRegistrationManager::class);
        $this->app->bind(CpaVesselsContract::class, CpaVesselsManager::class);
        $this->app->bind( SmContract::class, SmManager::class);
        $this->app->bind( cashCollectionContract::class, cashcCollectionManager::class);
        $this->app->bind( BsContract::class, BsManager::class);
        $this->app->bind( MooringChargeContract::class, MooringChargeManager::class);
        $this->app->bind( JettyServiceContract::class, JettyServiceManager::class);
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
