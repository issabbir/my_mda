<?php

namespace App\Managers\Mwe;

use App\Contracts\Mwe\InspectionReqContract;
use App\Entities\Mwe\MaintenanceInspectionJob;
use App\Entities\Mwe\MaintenanceInspector;
use App\Entities\Mwe\MaintenanceReq;
use App\Entities\Mwe\VesselInspection;
use App\Helpers\HelperClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDO;
use Yajra\Pdo\Oci8\Exceptions\Oci8Exception;

class InspectionReqManager implements InspectionReqContract
{

    public function storeInspectionReport($action_type = null, $request = [], $id = null)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_MAINTENANCE_REQ_ID" => $request->get('maintenance_req_id'),
                "P_INSPECTION_DATE" => empty($request->get("inspection_date")) ? null : date("Y-m-d", strtotime($request->get("inspection_date"))),
                "P_INSPECTION_APPROVER_EMP_ID" => auth()->user()->employee->emp_id,
                "P_SLIPWAY_ID" => $request->get("slipway_id"),
                "P_SLIPWAY_ASSIGNED_BY_EMP_ID" => auth()->user()->employee->emp_id,
                "P_WORKSHOP_ASSIGNED_BY_EMP_ID" => auth()->user()->employee->emp_id,
                "P_STATUS" => (!$request->get("status")) ? $request->get('prv_status') : $request->get("status"),
                "p_inspector_comment" => $request->get('inspector_comment'),
                "P_INSPECTOR_JOB_NUMBER" => $request->get('inspector_job_number'),
                "P_inspector_job_id" => $request->get('inspector_job_id'),
                "P_CREATED_BY" => auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_MAINT_REQ_PROCESS", $params);

            $response = [
                "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                "status_code" => $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }


    public function workshopAssign($request = [], $id = null)
    {
        $data = $request->get('workshop');//dd($data);
        DB::beginTransaction();
        try {
            $mst_status_code = sprintf("%4000s", "");
            $mst_status_message = sprintf("%4000s", "");
            $params = [
                "P_maintenance_req_id" => $request->get('maintenance_req_id'),
                "P_SAE_MECHANICAL_EMP_ID" => $request->get('sae_mechanical_emp_id'),
                "P_CREATED_BY" => auth()->id(),
                "O_STATUS_CODE" => &$mst_status_code,
                "O_STATUS_MESSAGE" => &$mst_status_message,
            ];
            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_MAINTENANCE_REQS_EMP_UPDATE", $params);

            //foreach ($data['inspection_id'] as $key => $value) {
            foreach ($data['workshop_id'] as $key => $value) {
                if ($data['workshop_id'][$key] != '') {
                    $inspection_id = $data['inspection_id'][$key];
                    $inspection_job_id = $data['inspection_job_id'][$key];
                    $maintenance_req_id = $data['maintenance_req_id'][$key];
                    $workshop_id = $data['workshop_id'][$key];
                    $workshop_sl_no = $data['workshop_sl_no'][$key];
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");

                    $params = [
                        "P_ID" => $inspection_id,
                        "p_MAINTENANCE_REQ_ID" => $maintenance_req_id,
                        "p_INSPECTION_JOB_ID" => $inspection_job_id,
                        "p_WORKSHOP_ID" => $workshop_id,
                        "p_WORKSHOP_SL_NO" => $workshop_sl_no,
                        "P_CREATED_BY" => auth()->id(),
                        "P_UPDATED_BY" => auth()->id(),
                        "O_STATUS_CODE" => &$status_code,
                        "O_STATUS_MESSAGE" => &$status_message,
                    ];
                    DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_VESSEL_WORKSHOP_ASSIGN", $params);
                    $response = [
                        "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                        "status_code" => $params['O_STATUS_CODE'],
                        "data" => $params,
                        "status_message" => $params['O_STATUS_MESSAGE']
                    ];
                }

            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function inspectionDatatable()
    {
        if ((Auth::user()->hasPermission('CAN_SAEN_MDA')) == 'true') {
            return MaintenanceReq::whereNotNull('inspector_emp_id')
                ->where('inspector_emp_id', auth()->user()->emp_id)
                ->with('vessel', 'department', 'vesselMaster', 'assignedInspector')
                ->orderBy("created_at", 'desc')->get();
        } else {
            return MaintenanceReq::whereNotNull('inspector_emp_id')
                ->with('vessel', 'department', 'vesselMaster', 'assignedInspector')
                ->orderBy("created_at", 'desc')->get();
        }
    }

    public function getInspection($id)
    {
        return MaintenanceReq::find($id)
            ->with('vessel', 'department', 'vesselMaster', 'assignedInspector')->first();
    }

    public function getVesselInspection($id)
    {

        return VesselInspection::where('maintenance_req_id', $id)
            ->whereIn('status', ['P', 'A'])
            ->with('inspection_job', 'workshop', 'ssaen_info', 'saen_info','maintenance_inspector')->get();
    }

    public function maintenanceInspectionJob($action_type = null, $request = [], $id = null)
    {

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID" => ["value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                "P_maintenance_req_id" => $request->get("maintenance_req_id"),
                "P_inspection_job_id" => $request->get("inspection_job_id"),
                "P_CREATED_BY" => auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];

            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_ADD_MAINT_INS_JOB_CUD", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                "status_code" => $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }


    public function maintenanceVesselInspector($action_type = null, $request = [], $id = null)
    {
        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "p_inspector_job_id" => $id,
                "p_assigned_ssae_emp_id" => $request->get("assigned_ssae_emp_id"),
                "p_assigned_sae_emp_id" => $request->get("assigned_sae_emp_id"),
                "P_maintenance_req_id" => $request->get("maintenance_req_id"),
                "P_CREATED_BY" => auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];

            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.mw_maintenance_inspector_cd", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                "status_code" => $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
            DB::commit();
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
            DB::rollBack();
        }
        return $response;
    }

    public function getMaintenanceInspector($id)
    {
        return MaintenanceInspector::where('maintenance_req_id', $id)
            ->whereIn('status', ['P', 'A'])
            ->with('assigned_ssae', 'assigned_sae')->get();
    }

    public function getInspectionOrderDataTable()
    {

        $this_data = MaintenanceInspector::where(function ($query) {
            $query->orWhere('assigned_ssae_emp_id', Auth::user()->emp_id)
                ->orWhere('assigned_sae_emp_id', Auth::user()->emp_id);
        })->get('maintenance_req_id');

        return MaintenanceReq::whereNotNull('inspector_emp_id')
            ->whereIn('id', $this_data)
            ->with('vessel', 'department', 'vesselMaster', 'assignedInspector')
            ->orderBy("created_at", 'desc')
            ->get();

    }

    public function getInspectionOrderJob($id)
    {
        $getKey = json_encode(Auth::user()->roles->pluck('role_key'));//dd($getKey);

        if(strpos($getKey, "MDA_SAE_M") == TRUE){
            return MaintenanceInspector::where('maintenance_req_id', $id)
                ->where('status', 'A')
                ->where('ASSIGNED_SAE_EMP_ID', Auth::user()->emp_id)
                ->orderBy('created_at', 'desc')
                ->get();
        }else if (strpos($getKey, "MDA_SSAE_M") == TRUE){
            return MaintenanceInspector::where('maintenance_req_id', $id)
                ->where('status', 'A')
                ->where('ASSIGNED_SSAE_EMP_ID', Auth::user()->emp_id)
                ->orderBy('created_at', 'desc')
                ->get();
        }else{
            return MaintenanceInspector::where('maintenance_req_id', $id)
                ->where('status', 'A')
                ->orderBy('created_at', 'desc')
                ->get();
        }
//dd(Auth::user()->emp_id);
//dd($id);


    }

    public function notifyReInspect($action_type = null, $request = [], $id = '')
    {//dd($request);
        $link = route("mwe.operation.inspection-order-view",[$request->get("maintenance_req_id")]);
        if (($pos = strpos($link, "/mwe")) !== FALSE) {
            $whatIWant = substr($link, $pos+1);
            $newLink = '/'.$whatIWant;
        }

        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID" => null,
                "P_MAINTENANCE_REQ_ID" => $request->get("maintenance_req_id"),
                "P_WEB_MESSAGE_LINK" => $newLink,//$request->get("inspection_job_type_id"),
                "P_WEB_MESSAGE_LINK_TITLE" => 'Inspection Cancellation',
                "P_WEB_NOTIFY_TO" => $request->get("inspector_emp_id"),
                "p_MESSAGE_BODY" => 'YOUR INSPECTION '.$request->get("inspection_job").' CANCELED.',
                "P_WEB_NOTIFY_READ_DATE" => date('Y-m-d H:i:s'),
                "P_CREATED_BY" => auth()->id(),
                "P_STATUS" => '1',
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];//dd($params);
            DB::executeProcedure("MDA.MDA_WEB_NOTIFICATIONS", $params);//dd($params);

            $response = [
                "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                "status_code" => $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
            DB::commit();
        } catch (\Exception $e) {//dd($e);
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
            DB::rollBack();
        }
        return $response;
    }

    public function storeInspectionJob($action_type = null, $request = [], $id = null)
    {
        $querys = "select * from MDA.MW_MAINTENANCE_INSPECTOR
WHERE INSPECTOR_JOB_ID = :INSPECTOR_JOB_ID" ;
        $data = db::selectOne($querys,['INSPECTOR_JOB_ID' => $request->get("inspector_job_id")]);
        if($data!=null){
            $inspector_job_number = $data->inspector_job_number;
        }else{
            $inspector_job_number = '';
        }//dd($inspector_job_number);
        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $inspection_job_type_id = $request->get("inspection_job_type_id");

            $params = [
                "P_ACTION_TYPE" => $action_type,
                "p_job_dtl_id" => ["value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                //"p_job_dtl_id" => $id,
                "p_inspector_job_id" => $request->get("inspector_job_id"),
                "p_inspection_job_type_id" => isset($inspection_job_type_id)?$inspection_job_type_id:'',//$request->get("inspection_job_type_id"),
                "P_FILES" => ['value' => $request->get("converted_file"), 'type' => SQLT_CLOB],
                "p_file_type" => $request->get("file_ext"),
                "p_remarks" => $request->get("remarks"),
                "p_inspector_job_number" => $inspector_job_number,
                "p_maintenance_req_id" => $request->get("maintenance_req_id"),
                "P_CREATED_BY" => auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.mw_main_inspection_job_dtl_cd", $params);

            $response = [
                "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                "status_code" => $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
            DB::commit();
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
            DB::rollBack();
        }
        return $response;
    }

    public function getMaintenanceInspection($id)
    {

        $getKey = json_encode(Auth::user()->roles->pluck('role_key'));//dd($getKey);

        if(strpos($getKey, "MDA_SAE_M") == TRUE){
            $this_data = MaintenanceInspector::where('maintenance_req_id', $id)
                ->where('ASSIGNED_SAE_EMP_ID', Auth::user()->emp_id)
                ->get('inspector_job_id');
        }else if (strpos($getKey, "MDA_SSAE_M") == TRUE){
            $this_data = MaintenanceInspector::where('maintenance_req_id', $id)
                ->where('ASSIGNED_SSAE_EMP_ID', Auth::user()->emp_id)
                ->get('inspector_job_id');
        }else{
            $this_data = MaintenanceInspector::where('maintenance_req_id', $id)
                ->get('inspector_job_id');
        }

        //$this_data = MaintenanceInspector::
        /**Each inspector can see other co-workers job list CR:16-03-2022**/
        /*where(function ($query) {
            $query->orWhere('assigned_ssae_emp_id', Auth::user()->emp_id)
                ->orWhere('assigned_sae_emp_id', Auth::user()->emp_id);
        })*/
        /*where('maintenance_req_id', $id)
            ->get('inspector_job_id');*/
        //dd($this_data);

        return MaintenanceInspectionJob::whereIn('inspector_job_id', $this_data)
            /**Each inspector can see other co-workers job list CR:16-03-2022**/
            /*->where('created_by',auth()->id())*/
            ->with('maintenance_inspector')
            ->whereIn('status', ['A', 'P'])
            ->with('inspection_job')
            ->orderBy("created_at", 'desc')
            ->get();

    }

    public function getInspectionByInspectorJob($id)
    {
        $querys = "SELECT MIJD.*, IJ.NAME, SU.EMP_ID, EMP.EMP_NAME FROM MDA.MW_MAIN_INSPECTION_JOB_DTL MIJD
LEFT JOIN MDA.MW_MAINTENANCE_INSPECTOR MI ON MI.INSPECTOR_JOB_ID = MIJD.INSPECTOR_JOB_ID
LEFT JOIN MDA.MW_INSPECTION_JOBS IJ ON IJ.ID = MIJD.INSPECTION_JOB_TYPE_ID
LEFT JOIN CPA_SECURITY.SEC_USERS SU ON MIJD.CREATED_BY = SU.USER_ID
LEFT JOIN PMIS.EMPLOYEE EMP ON EMP.EMP_ID = SU.EMP_ID
WHERE MIJD.INSPECTOR_JOB_ID = :INSPECTOR_JOB_ID
AND MIJD.STATUS = 'A'
AND MI.INSP_CONF_SSAE = 'Y'
ORDER BY MIJD.CREATED_BY DESC, MIJD.CREATED_AT DESC" ;
        $data = db::select($querys,['INSPECTOR_JOB_ID' => $id]);
        return $data;
        /*return MaintenanceInspectionJob::where('inspector_job_id', $id)
            ->with('inspection_job', 'inspection_creator')
            ->where('status', 'A')
            ->orderBy('created_by', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();*/
    }

    public function updateInspConfirmSSAE(Request $request)
    {//dd($request->all());
        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ID" => $request->get("main_req_id"),
                "P_SSAE_EMP_ID" => auth()->user()->employee->emp_id,
                "P_INSPECTOR_JOB_ID" => $request->get("inspector_job_id"),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.UPDATE_INSP_CONFIRM_SSAE", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                "status_code" => $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
            DB::commit();
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
            DB::rollBack();
        }
        return $response;
    }


}
