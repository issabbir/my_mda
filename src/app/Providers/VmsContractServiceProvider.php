<?php

namespace App\Providers;


use App\Contracts\LookupContract;
use App\Contracts\Mea\Brta\BrtaContract;
use App\Contracts\Mea\Common\Employee\EmployeeContract;
use App\Contracts\Mea\Nid\NidContract;
use App\Contracts\Mea\Vms\CommonContract;
use App\Managers\Mea\Brta\BrtaManager;
use App\Managers\Mea\Common\Employee\EmployeeManager;
use App\Managers\Mea\Nid\NidManager;
use App\Managers\Mea\Vms\CommonManager;
use Illuminate\Support\ServiceProvider;

class VmsContractServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(CommonContract::class,CommonManager::class);
        $this->app->bind(LookupContract::class,LookupContract::class);
        $this->app->bind(EmployeeContract::class,EmployeeManager::class);
        $this->app->bind(BrtaContract::class,BrtaManager::class);
        $this->app->bind(NidContract::class,NidManager::class);
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
