<?php

namespace App\Http\Controllers\Mwe;

use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Entities\Mwe\L_Thirdparty;
use App\Entities\Mwe\MaintenanceReq;
use App\Entities\Mwe\ThirdpartyAssign;
use App\Entities\Mwe\ThirdpartyRequest;
use App\Entities\Mwe\Unit;
use App\Entities\Mwe\Workshop;
use App\Http\Controllers\Controller;
use App\Managers\Mwe\InspectionReqManager;
use App\Managers\Mwe\WorkshopManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO;
use Yajra\DataTables\Facades\DataTables;

class ThirdPartyRequestsApprovalController extends Controller
{
    public function index()
    {
        $role_key = json_decode(Auth::user()->roles->pluck('role_key'));
        $role = '';
        if (in_array("MDA_SAE_M", $role_key)) {
            $role = 'MDA_SSAE_M';
        }else if (in_array("MDA_SSAE_M", $role_key)){
            $role = 'MDA_ASW';
        }else if (in_array("MDA_ASW", $role_key)){
            $role = 'MDA_XEN';
        }

        if(!empty($role)){
            $sql = "select emp.emp_id,emp.emp_code,emp.emp_name,sr.role_name
from PMIS.EMPLOYEE emp
left join cpa_security.SEC_USERS us on us.EMP_ID = emp.EMP_ID
left join CPA_SECURITY.SEC_USER_ROLES usr on usr.USER_ID = us.USER_ID
left join CPA_SECURITY.SEC_ROLE sr on sr.ROLE_ID = usr.ROLE_ID
where UPPER(sr.ROLE_KEY) = UPPER(:ROLE_KEY)";
            $users = db::select($sql,['ROLE_KEY' => $role]);
        }else{
            $users = '';
        }
        return view('mwe.thirdparty.request_approval',['users' => $users,]);
    }

    public function dataTableList()
    {
        /*$role_key = json_decode(Auth::user()->roles->pluck('role_key'));
        $where = '';
        if (in_array("MDA_SAE_M", $role_key)) {
            $where = 'WHERE TA.FORWARD_TO_SAE = '.auth()->user()->employee->emp_id;
        }else if (in_array("MDA_SSAE_M", $role_key)){
            $where = 'WHERE TA.FORWARD_TO_SSAE = '.auth()->user()->employee->emp_id;
        }else if (in_array("MDA_ASW", $role_key)){
            $where = 'WHERE TA.FORWARD_TO_ASW = '.auth()->user()->employee->emp_id;
        }else if (in_array("MDA_XEN", $role_key)){
            $where = 'WHERE TA.FORWARD_TO_XEN = '.auth()->user()->employee->emp_id;
        }*/
        $role_key = json_decode(Auth::user()->roles->pluck('role_key'));
        $where = '';
        if (in_array("MDA_SSAE_M", $role_key)){
            $where = 'WHERE TR.FORWARD_TO_SSAE = '.auth()->user()->employee->emp_id;
        }else if (in_array("MDA_ASW", $role_key)){
            $where = 'WHERE TR.FORWARD_TO_ASW = '.auth()->user()->employee->emp_id;
        }else if (in_array("MDA_XEN", $role_key)){
            $where = 'WHERE TR.FORWARD_TO_XEN = '.auth()->user()->employee->emp_id;
        }

        //$querys = "SELECT TA.FORWARD_TO_SAE, TA.FORWARD_TO_SSAE, TA.FORWARD_TO_ASW, TA.FORWARD_TO_XEN, TA.APPROVE_YN, TR.THIRDPARTY_REQ_ID,TR.REQUEST_DATE,IJ.NAME TASK, DEP.DEPARTMENT_NAME, V.NAME VESSEL_NAME, MI.INSPECTOR_JOB_NUMBER, MR.REQUEST_NUMBER, TP.THIRDPARTY_NAME FROM MDA.THIRDPARTY_REQUEST TR LEFT JOIN MDA.MW_VESSEL_INSPECTIONS VI ON TR.MW_VESSEL_INSPECTIONS_ID = VI.ID LEFT JOIN MDA.MW_INSPECTION_JOBS IJ ON IJ.ID = VI.INSPECTION_JOB_ID LEFT JOIN MDA.MW_MAINTENANCE_REQS MR ON MR.ID = TR.MAINTENANCE_REQ_ID LEFT JOIN MDA.VESSELS V ON V.ID = MR.VESSEL_ID LEFT JOIN PMIS.L_DEPARTMENT DEP ON DEP.DEPARTMENT_ID = MR.DEPARTMENT_ID LEFT JOIN MDA.MW_MAINTENANCE_INSPECTOR MI ON MI.MAINTENANCE_REQ_ID = TR.MAINTENANCE_REQ_ID LEFT JOIN MDA.THIRDPARTY_ASSIGN TA ON TA.THIRDPARTY_REQ_ID = TR.THIRDPARTY_REQ_ID LEFT JOIN MDA.L_THIRDPARTY TP ON TP.THIRDPARTY_ID = TA.THIRDPARTY_ID $where ORDER BY TR.INSERT_DATE DESC" ;
        $querys = "SELECT TR.*,IJ.NAME,MR.VESSEL_MASTER_ID,MR.REQUEST_NUMBER, V.NAME VESSEL_NAME,DEP.DEPARTMENT_NAME, VI.JOB_NUMBER INSPECTOR_JOB_NUMBER, TP.THIRDPARTY_NAME
       FROM MDA.THIRDPARTY_REQUEST TR
       LEFT JOIN MDA.MW_VESSEL_INSPECTIONS VI ON TR.MW_VESSEL_INSPECTIONS_ID = VI.ID
       LEFT JOIN MDA.MW_INSPECTION_JOBS IJ ON IJ.ID = VI.INSPECTION_JOB_ID
       LEFT JOIN MDA.MW_MAINTENANCE_REQS MR ON MR.ID = TR.MAINTENANCE_REQ_ID
       LEFT JOIN MDA.CPA_VESSELS V ON V.ID = MR.VESSEL_ID
       LEFT JOIN PMIS.L_DEPARTMENT DEP ON DEP.DEPARTMENT_ID = MR.DEPARTMENT_ID
       LEFT JOIN MDA.THIRDPARTY_ASSIGN TA ON TA.THIRDPARTY_REQ_ID = TR.THIRDPARTY_REQ_ID
       LEFT JOIN MDA.L_THIRDPARTY TP ON TP.THIRDPARTY_ID = TA.THIRDPARTY_ID
       $where
       ORDER BY TR.INSERT_DATE DESC" ;
        $queryResult = DB::select($querys);

        return datatables()->of($queryResult)
            ->addColumn('request_date', function ($query) {
                if ($query->request_date == null) {
                    return '--';

                } else {
                    return Carbon::parse($query->request_date)->format('d-m-Y h:i A');
                }
            })
            ->addColumn('action', function ($query) {
                $role_key = json_decode(Auth::user()->roles->pluck('role_key'));
                if (in_array("MDA_SSAE_M", $role_key)){
                    if($query->forward_to_asw==null){
                        $actionBtn = '<a title="Forward" data-thirdpartyreqid="'.$query->thirdparty_req_id.'" href="javascript:void(0)" class="show-receive-modal approveBtn"><i class="bx bx-fast-forward-circle cursor-pointer" style="color: #0E9A00"></i></a>';
                        return $actionBtn;
                    }else{
                        if($query->approve_yn=='N') {
                            $forward = '';
                            $desig = '';
                            if ($query->forward_to_xen != null) {
                                $forward = $query->forward_to_xen;
                                $desig = 'XEN';
                            } else if ($query->forward_to_asw != null) {
                                $forward = $query->forward_to_asw;
                                $desig = 'ASW';
                            }
                            $sql = "select emp.emp_code ,emp.emp_name,sr.role_name
from PMIS.EMPLOYEE emp
left join cpa_security.SEC_USERS us on us.EMP_ID = emp.EMP_ID
left join CPA_SECURITY.SEC_USER_ROLES usr on usr.USER_ID = us.USER_ID
left join CPA_SECURITY.SEC_ROLE sr on sr.ROLE_ID = usr.ROLE_ID
where emp.EMP_ID = :EMP_ID";
                            $data = db::selectOne($sql, ['EMP_ID' => $forward]);
                            $html = <<<HTML
<span class="badge badge-success"> Forwarded: $data->emp_name ($desig)</span>
HTML;
                            return $html;
                        }else{
                            $html = <<<HTML
<span class="badge badge-success"> Task Approved</span>
HTML;
                            return $html;
                        }
                    }
                }else if (in_array("MDA_ASW", $role_key)){
                    if($query->forward_to_xen==null){
                        $actionBtn = '<a title="Forward" data-thirdpartyreqid="'.$query->thirdparty_req_id.'" href="javascript:void(0)" class="show-receive-modal approveBtn"><i class="bx bx-fast-forward-circle cursor-pointer" style="color: #0E9A00"></i></a>';
                        return $actionBtn;
                    }else{
                        if($query->approve_yn=='N') {
                            $forward = '';
                            $desig = '';
                            if ($query->forward_to_xen != null) {
                                $forward = $query->forward_to_xen;
                                $desig = 'XEN';
                            }
                            $sql = "select emp.emp_code ,emp.emp_name,sr.role_name
from PMIS.EMPLOYEE emp
left join cpa_security.SEC_USERS us on us.EMP_ID = emp.EMP_ID
left join CPA_SECURITY.SEC_USER_ROLES usr on usr.USER_ID = us.USER_ID
left join CPA_SECURITY.SEC_ROLE sr on sr.ROLE_ID = usr.ROLE_ID
where emp.EMP_ID = :EMP_ID";
                            $data = db::selectOne($sql, ['EMP_ID' => $forward]);
                            $html = <<<HTML
<span class="badge badge-success"> Forwarded: $data->emp_name ($desig)</span>
HTML;
                            return $html;
                        }else{
                            $html = <<<HTML
<span class="badge badge-success"> Task Approved</span>
HTML;
                            return $html;
                        }
                    }
                }else if (in_array("MDA_XEN", $role_key)){
                    if($query->approve_yn=='N'){
                        $actionBtn = '<a title="Forward" data-thirdpartyreqid="'.$query->thirdparty_req_id.'" href="javascript:void(0)" class="show-receive-modal approveBtn"><i class="bx bx-fast-forward-circle cursor-pointer" style="color: #0E9A00"></i></a>';
                        return $actionBtn;
                    }else{
                        $html = <<<HTML
<span class="badge badge-success"> Task Approved</span>
HTML;
                        return $html;
                    }
                }else{
                    return '';
                }


            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function onlyDtldataTable(Request $request)
    {
        $maintenance_req_id = $request->get("maintenance_req_id");
        $mw_workshop_id = $request->get("mw_workshop_id");
        $mw_vessel_inspections_id = $request->get("mw_vessel_inspections_id");
        $querys = "SELECT * FROM MDA.THIRDPARTY_TASK_MONITOR
WHERE MAINTENANCE_REQ_ID = :MAINTENANCE_REQ_ID
AND MW_VESSEL_INSPECTIONS_ID = :MW_VESSEL_INSPECTIONS_ID
AND MW_WORKSHOP_ID = :MW_WORKSHOP_ID
ORDER BY INSERT_DATE DESC" ;
        $queryResult = db::select($querys,['MAINTENANCE_REQ_ID' => $maintenance_req_id, 'MW_VESSEL_INSPECTIONS_ID' => $mw_vessel_inspections_id, 'MW_WORKSHOP_ID' => $mw_workshop_id]);
        return datatables()->of($queryResult)
            ->addIndexColumn()
            ->escapeColumns([])
            ->make(true);
    }

    public function getDtlData($maintenance_req_id)
    {
        $sql = "SELECT * from MDA.THIRDPARTY_REQUEST where thirdparty_req_id = :THIRDPARTY_REQ_ID";
        $mst = db::selectOne($sql,['THIRDPARTY_REQ_ID' => $maintenance_req_id]);

        $sql = "SELECT * from MDA.THIRDPARTY_TASK_MONITOR where thirdparty_req_id = :THIRDPARTY_REQ_ID ORDER BY INSERT_DATE ASC";
        $dtl = db::select($sql,['THIRDPARTY_REQ_ID' => $maintenance_req_id]);

        return  response(
            [
                'mst_data' => $mst,
                'dtl_data' => $dtl,
            ]
        );
    }

    public function addNewDtl(Request $request)
    {//dd($request->all());

        DB::beginTransaction();

        try {
            $id = null;
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_TASK_MONITOR_ID" => $id,
                "P_DESCRIPTION" => $request->get("description"),
                "P_TASK_STATUS_ID" => '1',
                "P_VESSEL_INSPECTION_ID" => $request->get("vessel_inspection_id"),
                "P_MW_WORKSHOP_ID" => $request->get("workshopId"),
                "P_MAINTENANCE_REQ_ID" => $request->get("maintenance_req_id"),
                "P_THIRDPARTY_REQ_ID" => $request->get("thirdparty_req_id"),
                "P_INSERT_BY" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message
            ];
            DB::executeProcedure("MDA.THIRDPARTY_SERVICE.APPROVAL_TASK_MONITORING_INS", $params);

            $lastParams = $params;

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                $params['html'] = view('mwe.thirdparty.message')->with('params', $params)->render();
            }

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return ["exception" => true, "o_status_code" => false, "o_status_message" => $exception->getMessage()];
        }

        $lastParams['html'] = view('mwe.thirdparty.message')->with('params', $lastParams)->render();
        return $lastParams;
    }

    public function forwardData(Request $request)
    {//dd($request->all());

        $role_key = json_decode(Auth::user()->roles->pluck('role_key'));
        $role = '';
        if (in_array("MDA_SAE_M", $role_key)) {
            $role = 'MDA_SAE_M';
        }else if (in_array("MDA_SSAE_M", $role_key)){
            $role = 'MDA_SSAE_M';
        }else if (in_array("MDA_ASW", $role_key)){
            $role = 'MDA_ASW';
        }else if (in_array("MDA_XEN", $role_key)){
            $role = 'MDA_XEN';
        }

        $lastParams = [];
        DB::beginTransaction();

        try {
            $id = null;
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_THIRDPARTY_REQ_ID" => $request->get("thirdparty_req_id"),
                "P_REMARKS" => $request->get("remarks"),
                "P_FORWARD_TO" => $request->get("forward_to"),
                "P_ROLE_KEY" => $role,
                "P_INSERT_BY" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message
            ];//dd($params);
            DB::executeProcedure("MDA.THIRDPARTY_SERVICE.THIRDPARTY_ASSIGN_UPD", $params);

            $lastParams = $params;

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                $params['html'] = view('mwe.thirdparty.message')->with('params', $params)->render();
            }

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return ["exception" => true, "o_status_code" => false, "o_status_message" => $exception->getMessage()];
        }

        $lastParams['html'] = view('mwe.thirdparty.message')->with('params', $lastParams)->render();
        return $lastParams;
    }
}
