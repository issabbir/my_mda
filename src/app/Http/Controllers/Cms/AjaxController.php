<?php

namespace App\Http\Controllers\Cms;

use App\Contracts\Cms\CommonContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public $employeeManger;
    public $commonManager;

    public function __construct(EmployeeContract $employeeManger, CommonContract $commonManager)
    {

        $this->employeeManger = $employeeManger;
        $this->commonManager=$commonManager;
    }

    public function searchEmployee(Request $request){
        return $this->commonManager->searchShiftingEmployee($request->get('search_param'));
    }
    public function getEmployeeInfo(Request $request){
        return $this->commonManager->getEmployee($request->get('id'));
    }

    public function showEmpDesignation(Request $request){
        return $this->employeeManger->findEmployeeInformation($request->get('id'));
    }

    public function showOffDayListByYearMonth(Request $request){
        $offday[] = "<option value=''>Select offday</option>";
            foreach ($this->commonManager->getOffDayList($request->get('duty_year'),$request->get('duty_month')) as $data){
                $offday[] = "<option  value='".$data['value']."'>{$data['value']}</option>";
            }
        return $offday;
    }



}
