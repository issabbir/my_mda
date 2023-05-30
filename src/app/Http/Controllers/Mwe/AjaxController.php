<?php

namespace App\Http\Controllers\Mwe;

use App\Contracts\Mwe\MaintenanceReqContract;
use App\Contracts\Mwe\WorkshopContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public $employeeManger;
    public $workshopManager;
    public $maintenanceReqManager;
//This controller is using SSAE & SAE
    public function __construct(EmployeeContract $employeeManger,WorkshopContract $workshopManager,MaintenanceReqContract $maintenanceReqManager)
    {

        $this->employeeManger = $employeeManger;
        $this->workshopManager = $workshopManager;
        $this->maintenanceReqManager = $maintenanceReqManager;
    }

    public function searchEmployee(Request $request){
        return $this->employeeManger->searchEmployee($request->get('search_param'));
    }

    public function searchVesselMaster(Request $request){
        return $this->employeeManger->searchVesselMaster($request->get('search_param'));
    }
    public function searchDocMaster(Request $request){
        return $this->employeeManger->searchVesselMaster($request->get('search_param'));
    }
    public function searchMaintenanceSAEN(Request $request){
        return $this->employeeManger->searchMaintenanceSAEN($request->get('search_param'));
    }
    public function searchMaintenanceSSAEN(Request $request){
        return $this->employeeManger->searchMaintenanceSSAEN($request->get('search_param'));
    }
    public function searchProduct(Request $request){
        return $this->workshopManager->searchProduct($request->get('search_param'));
    }
    public function searchVessel(Request $request){
        return $this->maintenanceReqManager->searchVessel($request->get('search_param'));
    }
    public function getVesselMaster(Request $request){
        return $this->maintenanceReqManager->showVesselMaster($request->get('id'));
    }

    public function searchMaintenanceRequest(Request $request){
        return $this->maintenanceReqManager->searchMaintenanceRequest($request->get('term'));
    }

    public function getMaintennanceRequestNumber(Request $request){

       return HelperClass::getRequestNumber($request->get('id'));
    }
}
