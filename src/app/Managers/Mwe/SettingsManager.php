<?php
namespace App\Managers\Mwe;


use App\Contracts\Mwe\SettingsContract;
use App\Entities\Mda\CompanyVesselInfo;
use App\Entities\Mda\LCompanyInfo;
use App\Entities\Mwe\InspectionJob;
use App\Entities\Mwe\MaintenanceSchedule;
use App\Entities\Mwe\Product;
use App\Entities\Mwe\Slipway;
use App\Entities\Mwe\Unit;
use App\Entities\Mwe\Works;
use App\Entities\Mwe\Workshop;
use App\Entities\Pmis\Employee\Employee;
use Illuminate\Support\Facades\DB;

class SettingsManager implements SettingsContract
{
    public function slipwayCud($action_type=null, $request = [], $id=0)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_NAME" => $request->get("name"),
                "p_DESCRIPTION" => $request->get("description"),
//                doc_master_id  "p_DESCRIPTION" => $request->get("description"),
                "p_CAPACITY" => $request->get("capacity"),
                "p_CAPACITY" => $request->get("doc_master"),
                "p_AUTHORIZED_BY" =>auth()->id(),
                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?'':auth()->id(),
                "P_STATUS" => $request->get('status'),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_SLIPWAYS_CUD", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            dd($e);
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function slipwayDatatable()
    {
        return Slipway::Where('status', '!=', 'D')->orderBy("created_at", 'desc')->get();
    }

    public function maintenanceScheduleCud($action_type=null, $request = [], $id=null)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_MAINTENANCE_REQ_ID"  => $request->get("maintenance_req_id"),
                "P_VESSEL_ID" => $request->get("vessel_id"),
                "P_VESSEL_MASTER_ID" => $request->get("vessel_master_id"),
                "P_DOC_MASTER_ID" => $request->get("doc_master_id"),
                "P_DEPARTMENT_ID" => $request->get("department_id"),
                "P_LAST_MAINTENANCE_AT" =>(!$request->get("last_maintenance_at"))?'':date("Y-m-d", strtotime($request->get("last_maintenance_at"))),
                "P_NEXT_MAINTENANCE_AT" =>date("Y-m-d", strtotime($request->get("next_maintenance_at"))),
                "P_last_undocking_at" =>date("Y-m-d", strtotime($request->get("last_undocking_at"))),
                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?'':auth()->id(),
                "P_STATUS" => $request->get('status'),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_MAINTENANCE_SCHEDULE_CUD", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function maintenanceScheduleDatatable()
    {
        return MaintenanceSchedule::whereIn('status', ['P','I'])
                                    ->with('vessel','department')
                                    ->orderBy("created_at", 'desc')->get();
    }

    public function getLastVesselMaintenanceDate($id)
    {
        return MaintenanceSchedule::where('vessel_id',$id)
                                       ->where('status','A')
                                       ->max('last_maintenance_at');
    }

    public function workshopCud($action_type=null, $request = [], $id=0)
    {

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_NAME" => $request->get("name"),
                "p_DESCRIPTION" => $request->get("description"),
                "p_LOCATION" =>$request->get('location'),
                "p_IN_CHARGED_EMP_ID" =>$request->get('in_charged_emp_id'),
                "p_SAEN_EMP_ID" =>$request->get('saen_emp_id'),
                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?'':auth()->id(),
                "P_STATUS" => $request->get('status'),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_WORKSHOPS_CUD", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function workshopDatatable()
    {
        return Workshop::Where('status', '!=', 'D')
               ->orderBy("created_at", 'desc')->get();
    }

    public function unitCud($action_type = null, $request = [], $id = null)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_NAME" => $request->get("name"),
                "p_DESCRIPTION" => $request->get("description"),
                "P_STATUS" => $request->get('status'),
                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?'':auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_UNITS_CUD", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function unitDatatable()
    {
        return Unit::Where('status', '!=', 'D')
            ->orderBy("created_at", 'desc')->get();
    }

    public  function inspectionJobCud($action_type = null, $request = [], $id = null)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_NAME" => $request->get("name"),
                "p_DESCRIPTION" => $request->get("description"),
                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?'':auth()->id(),
                "P_STATUS" => $request->get('status'),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_INSPECTION_JOBS_CUD", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function inspectionJobDatatable()
    {
        return InspectionJob::Where('status', '!=', 'D')
            ->orderBy("created_at", 'desc')->get();
    }

    public function productDatatable()
    {
        return Product::Where('active_yn', 'Y')->orWhere('active_yn', 'N')
            ->orderBy("insert_date", 'desc')->get();
    }

    public  function productCud($action_type = null, $request = [], $id = null)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_NAME" => $request->get("name"),
                "p_DESCRIPTION" => $request->get("description"),
                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?'':auth()->id(),
                "P_STATUS" => $request->get('status'),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_PRODUCTS_CUD", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public  function empOfcCud($action_type = null, $request = [], $id = null)
    {
        $oName = DB::table('MDA.L_LICENSE_OFFICE')->where('office_id',$request->get('office_id'))->get(['office_name'])->first();
        if(isset($oName)){
            $oName = $oName->office_name;
        }else{
            $oName = null;
        }

        $empName = DB::table('PMIS.EMPLOYEE')->where('emp_id',$request->get('emp_id'))->first();
        if(isset($empName)){
            $eName = $empName->emp_name;
            $eCode = $empName->emp_code;
        }else{
            $eName = null;
            $eCode = null;
        }

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_OFFICE_ID" => $request->get("office_id"),
                "P_OFFICE_NAME" => $oName,
                "P_EMP_ID" => $request->get("emp_id"),
                "P_EMP_NAME" => $eName,
                "P_EMP_CODE" => $eCode,
                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?'':auth()->id(),
                "P_STATUS" => $request->get('status'),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_EMPLOYEE_OFFICE_SETUP_CUD", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function workDatatable()
    {
        return Works::Where('active_yn', '!=', 'D')
            ->orderBy("insert_date", 'desc')->get();
    }

    public function companyDatatable()
    {
        return LCompanyInfo::Where('active_yn', '!=', 'D')
            ->orderBy("insert_date", 'desc')->get();
    }

    public function companyVesselDatatable()
    {
        return CompanyVesselInfo::Where('active_yn', '!=', 'D')
            ->orderBy("insert_date", 'desc')->get();
    }

    public  function workCud($action_type = null, $request = [], $id = null)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_NAME" => $request->get("name"),
                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?auth()->id():'',
                "P_STATUS" => $request->get('status'),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_WORKS_CUD", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

}
