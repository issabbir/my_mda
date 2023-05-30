<?php

namespace App\Managers\Mwe;

use App\Contracts\Mwe\MaintenanceReqContract;
use App\Entities\Mwe\MaintenanceReq;
use App\Entities\Mwe\Vessel;
use App\Entities\Pmis\Employee\Employee;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MaintenanceReqManager implements MaintenanceReqContract
{
    protected $vessel;

    public function __construct(Vessel $vessel)
    {
        $this->vessel = $vessel;
    }

    public function maintenanceCud($action_type = null, $request = [], $id = null)
    {
        $incharge = $request->get("incharge_emp_id");
        if($incharge!=''){
            try {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params = [
                    "P_ID" => $request->get("vessel_id"),
                    "P_INCH_EMP_ID" => $incharge,
                    "P_UPDATED_BY" => auth()->id(),
                    "O_STATUS_CODE" => &$status_code,
                    "O_STATUS_MESSAGE" => &$status_message,
                ];
                DB::executeProcedure("MDA.UPDATE_VESSELS_INCHARGE", $params);
            } catch (\Exception $e) {
                //$response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
            }


            try {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params = [
                    "P_ACTION_TYPE" => $action_type,
                    "P_ID" => ["value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                    "P_REQUEST_NUMBER" => $request->get("request_number"),
                    "P_DEPARTMENT_ID" => $request->get("department_id"),
                    "P_VESSEL_ID" => $request->get("vessel_id"),
                    "P_VESSEL_MASTER_ID" => $incharge,
                    "p_DESCRIPTION" => $request->get("description"),
                    "P_REQUESTER_EMP_ID" => auth()->user()->employee->emp_id,
                    "p_IS_SCHEDULE_REQUEST" => 'N',
                    "P_CREATED_BY" => ($action_type == 'I') ? auth()->id() : '',
                    "P_UPDATED_BY" => ($action_type != 'I') ? '' : auth()->id(),
                    "P_STATUS" => '',
                    "O_STATUS_CODE" => &$status_code,
                    "O_STATUS_MESSAGE" => &$status_message,
                ];
                DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_MAINT_REQS_INI_CUD", $params);
                $response = [
                    "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                    "status_code" => $params['O_STATUS_CODE'],
                    "data" => $params,
                    "status_message" => $params['O_STATUS_MESSAGE']
                ];
                if ($action_type != "D"){
                    $byteCode="";
                    $fileExt = "";
                    $fTid = 0;
                    $status_code2 = sprintf("%4000s","");
                    $status_message2 = sprintf("%4000s", "");
                    $title = "MAINTENANCE_REQUEST";
                    if ($request->hasFile("attachment")) {
                        $fTid = ($request->get('pre_attachment_id') != "") ? $request->get('pre_attachment_id') : 0;
                        $action_type = ($request->get('pre_attachment_id') != "") ? 'U' : 'I';
                        $files = $request->file("attachment");
                        $byteCode = base64_encode(file_get_contents($files->getRealPath()));
                        $fileExt = $files->extension();
                    }

                    $params2 = [
                        "P_ACTION_TYPE" => $action_type,
                        "P_ID" => ["value"=>&$fTid, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                        "P_FILES" => [
                            'value' => $byteCode,
                            'type'  => \PDO::PARAM_LOB
                        ],
                        "P_STATUS" => "A",
                        "P_CREATED_BY" => auth()->id(),
                        "P_UPDATED_BY" => auth()->id(),
                        "P_SOURCE_TABLE" => "MW_MAINTENANCE_REQS",
                        "P_REF_ID" => $params["P_ID"],
                        "P_TITLE" => $title,
                        "P_FILE_TYPE" => $fileExt,
                        "O_STATUS_CODE" => &$status_code2,
                        "O_STATUS_MESSAGE" => &$status_message2
                    ];

                    DB::executeProcedure("MDA.MDA_CORE_PROCE.MEDIA_FILES_CUD", $params2);
                    if ($params2["O_STATUS_CODE"] != "1"){
                        throw new \Exception($params["O_STATUS_MESSAGE"]." IN MEDIA_FILES_CUD");
                    }
                }
            } catch (\Exception $e) {
                $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
            }
        }else{
            try {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params = [
                    "P_ACTION_TYPE" => $action_type,
                    "P_ID" => ["value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                    "P_REQUEST_NUMBER" => $request->get("request_number"),
                    "P_DEPARTMENT_ID" => $request->get("department_id"),
                    "P_VESSEL_ID" => $request->get("vessel_id"),
                    "P_VESSEL_MASTER_ID" => $request->get("vessel_master_id"),
                    "p_DESCRIPTION" => $request->get("description"),
                    "P_REQUESTER_EMP_ID" => auth()->user()->employee->emp_id,
                    "p_IS_SCHEDULE_REQUEST" => 'N',
                    "P_CREATED_BY" => ($action_type == 'I') ? auth()->id() : '',
                    "P_UPDATED_BY" => ($action_type != 'I') ? '' : auth()->id(),
                    "P_STATUS" => '',
                    "O_STATUS_CODE" => &$status_code,
                    "O_STATUS_MESSAGE" => &$status_message,
                ];
                DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_MAINT_REQS_INI_CUD", $params);
                $response = [
                    "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                    "status_code" => $params['O_STATUS_CODE'],
                    "data" => $params,
                    "status_message" => $params['O_STATUS_MESSAGE']
                ];
                if ($action_type != "D"){
                    $byteCode="";
                    $fileExt = "";
                    $fTid = 0;
                    $status_code2 = sprintf("%4000s","");
                    $status_message2 = sprintf("%4000s", "");
                    $title = "MAINTENANCE_REQUEST";
                    if ($request->hasFile("attachment")) {
                        $fTid = ($request->get('pre_attachment_id') != "") ? $request->get('pre_attachment_id') : 0;
                        $action_type = ($request->get('pre_attachment_id') != "") ? 'U' : 'I';
                        $files = $request->file("attachment");
                        $byteCode = base64_encode(file_get_contents($files->getRealPath()));
                        $fileExt = $files->extension();
                    }

                    $params2 = [
                        "P_ACTION_TYPE" => $action_type,
                        "P_ID" => ["value"=>&$fTid, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                        "P_FILES" => [
                            'value' => $byteCode,
                            'type'  => \PDO::PARAM_LOB
                        ],
                        "P_STATUS" => "A",
                        "P_CREATED_BY" => auth()->id(),
                        "P_UPDATED_BY" => auth()->id(),
                        "P_SOURCE_TABLE" => "MW_MAINTENANCE_REQS",
                        "P_REF_ID" => $params["P_ID"],
                        "P_TITLE" => $title,
                        "P_FILE_TYPE" => $fileExt,
                        "O_STATUS_CODE" => &$status_code2,
                        "O_STATUS_MESSAGE" => &$status_message2
                    ];

                    DB::executeProcedure("MDA.MDA_CORE_PROCE.MEDIA_FILES_CUD", $params2);
                    if ($params2["O_STATUS_CODE"] != "1"){
                        throw new \Exception($params["O_STATUS_MESSAGE"]." IN MEDIA_FILES_CUD");
                    }
                }
            } catch (\Exception $e) {
                $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
            }
        }

        return $response;
    }

    public function maintenanceDatatable()
    {
        if((Auth::user()->hasPermission('CAN_XEN_MDA') ||Auth::user()->hasPermission('CAN_DEPUTY_CHIEF_ENG_MDA'))=='true'){
            return MaintenanceReq::with('vessel', 'department', 'vesselMaster', 'assignedBy')
                ->orderBy("created_at", 'desc')->get();
        }else{
            return MaintenanceReq::where('REQUESTER_EMP_ID', auth()->user()->emp_id)->with('vessel', 'department', 'vesselMaster', 'assignedBy')
                ->orderBy("created_at", 'desc')->get();
        }

    }

    public function searchVessel($name)
    {
       /*return $vessels = DB::table('mda.mw_vessels')
            ->leftJoin('pmis.employee', 'mda.mw_vessels.VESSEL_MASTER_EMP_ID', '=', 'pmis.employee.emp_id')
            ->where(DB::raw('LOWER(mda.mw_vessels.name)'), 'like', strtolower('%' . trim($name) . '%'))
            ->get(['id','name','emp_name','vessel_master_emp_id']);*/
        return $vessels = DB::table('mda.CPA_VESSELS')
            ->leftJoin('pmis.employee', 'mda.CPA_VESSELS.INCHARGE_EMP_ID', '=', 'pmis.employee.emp_id')
            ->where(DB::raw('LOWER(mda.CPA_VESSELS.name)'), 'like', strtolower('%' . trim($name) . '%'))
            ->where('STATUS','A')
            ->limit(10)
            ->orderBy('name', 'ASC')
            ->get(['id','name','emp_name','incharge_emp_id']);
    }

    public function showVesselMaster($id)
    {
        return $vessels =DB::table('mda.CPA_VESSELS')
            ->leftJoin('pmis.employee', 'mda.CPA_VESSELS.INCHARGE_EMP_ID', '=', 'pmis.employee.emp_id')
            ->where(DB::raw('LOWER(mda.CPA_VESSELS.id)'), '=',$id)
            ->get(['id','name','emp_name','incharge_emp_id']);
    }

    public function xenAuthorization($action_type = null, $request = [], $id = null)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ID" => $id,
                "REQUEST_APPROVER_EMP_ID" => auth()->user()->employee->emp_id,
                "P_INSPECTOR_ASSIGNED_BY_EMP_ID" => auth()->user()->employee->emp_id,
                "P_INSPECTOR_EMP_ID" => $request->get("inspector_emp_id"),
                "P_REMARKS_XEN" => $request->get("remarks_xen"),
                "P_UPDATED_BY" => auth()->id(),
                "P_STATUS" => empty($request->get("status"))?$request->get("current_status"):strtoupper($request->get("status")),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_MAINT_REQ_AUTHORISATION", $params);
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

    public function deputyChiefEngAuthorization($action_type = null, $request = [], $id = null)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ID" => $id,
                "P_DEPUTY_CHIEF_ENG_USER_ID" => auth()->id(),
                "P_DEPUTY_CHIEF_ENG_COMMENT" => $request->get('deputy_chief_eng_comment'),
                "P_DEPUTY_CHIEF_ENG_APP_STATUS" => empty($request->get("deputy_chief_eng_app_status"))?$request->get("deputy_chief_eng_app_status"):strtoupper($request->get("deputy_chief_eng_app_status")),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_MAINT_REQ_DCEN_APPROVAL", $params);
//            dd($params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                "status_code" => $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
//            dd($e->getMessage());
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function searchMaintenanceRequest($name){
        $name = trim(strtolower($name));
        return $request = DB::table('MDA.MW_MAINTENANCE_REQS')
            ->where(DB::raw('LOWER(mda.MW_MAINTENANCE_REQS.REQUEST_NUMBER)'), 'LIKE', '%' . $name . '%')
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get(['id','request_number']);
    }



}
