<?php

namespace App\Http\Controllers\Mwe;

use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Entities\Mwe\L_Thirdparty;
use App\Entities\Mwe\MaintenanceReq;
use App\Entities\Mwe\ThirdpartyAssign;
use App\Entities\Mwe\ThirdpartyRequest;
use App\Entities\Mwe\ThirdpartyTaskMonitor;
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

class ThirdPartyTaskMonitorController extends Controller
{
    public function index()
    {
        return view('mwe.thirdparty.thirdparty_tasks');
    }

    public function dataTableList()
    {
        $role_key = json_decode(Auth::user()->roles->pluck('role_key'));
        $role = '';
        $emp_id = auth()->user()->employee->emp_id;
        if (in_array("MDA_SAE_M", $role_key)) {
            $querys = "SELECT TR.*,IJ.NAME, DEP.DEPARTMENT_NAME, V.NAME VESSEL_NAME, MR.VESSEL_MASTER_ID, VI.JOB_NUMBER INSPECTOR_JOB_NUMBER, MR.REQUEST_NUMBER, TP.THIRDPARTY_NAME
       FROM MDA.THIRDPARTY_REQUEST TR
       LEFT JOIN MDA.MW_VESSEL_INSPECTIONS VI ON TR.MW_VESSEL_INSPECTIONS_ID = VI.ID
       LEFT JOIN MDA.MW_INSPECTION_JOBS IJ ON IJ.ID = VI.INSPECTION_JOB_ID
       LEFT JOIN MDA.MW_MAINTENANCE_REQS MR ON MR.ID = TR.MAINTENANCE_REQ_ID
       LEFT JOIN MDA.CPA_VESSELS V ON V.ID = MR.VESSEL_ID
       LEFT JOIN PMIS.L_DEPARTMENT DEP ON DEP.DEPARTMENT_ID = MR.DEPARTMENT_ID
       LEFT JOIN MDA.THIRDPARTY_ASSIGN TA ON TA.THIRDPARTY_REQ_ID = TR.THIRDPARTY_REQ_ID
       LEFT JOIN MDA.L_THIRDPARTY TP ON TP.THIRDPARTY_ID = TA.THIRDPARTY_ID
       LEFT JOIN CPA_SECURITY.SEC_USERS SU ON SU.USER_ID = TR.INSERT_BY
       WHERE TR.APPROVE_YN = 'Y'
       AND SU.EMP_ID = $emp_id
       AND VI.TP_REQ_YN != 'C'
       ORDER BY TR.INSERT_DATE DESC" ;
        }else if (in_array("MDA_SSAE_M", $role_key)){
            $querys = "SELECT TR.*,IJ.NAME, DEP.DEPARTMENT_NAME, V.NAME VESSEL_NAME, MR.VESSEL_MASTER_ID, VI.JOB_NUMBER INSPECTOR_JOB_NUMBER, MR.REQUEST_NUMBER, TP.THIRDPARTY_NAME
       FROM MDA.THIRDPARTY_REQUEST TR
       LEFT JOIN MDA.MW_VESSEL_INSPECTIONS VI ON TR.MW_VESSEL_INSPECTIONS_ID = VI.ID
       LEFT JOIN MDA.MW_INSPECTION_JOBS IJ ON IJ.ID = VI.INSPECTION_JOB_ID
       LEFT JOIN MDA.MW_MAINTENANCE_REQS MR ON MR.ID = TR.MAINTENANCE_REQ_ID
       LEFT JOIN MDA.CPA_VESSELS V ON V.ID = MR.VESSEL_ID
       LEFT JOIN PMIS.L_DEPARTMENT DEP ON DEP.DEPARTMENT_ID = MR.DEPARTMENT_ID
       LEFT JOIN MDA.THIRDPARTY_ASSIGN TA ON TA.THIRDPARTY_REQ_ID = TR.THIRDPARTY_REQ_ID
       LEFT JOIN MDA.L_THIRDPARTY TP ON TP.THIRDPARTY_ID = TA.THIRDPARTY_ID
       WHERE TR.APPROVE_YN = 'Y'
       AND TR.FORWARD_TO_SSAE = $emp_id
       AND VI.TP_REQ_YN != 'C'
       ORDER BY TR.INSERT_DATE DESC" ;
        }else if (in_array("MDA_ASW", $role_key)){
            $querys = "SELECT TR.*,IJ.NAME, DEP.DEPARTMENT_NAME, V.NAME VESSEL_NAME, MR.VESSEL_MASTER_ID, VI.JOB_NUMBER INSPECTOR_JOB_NUMBER, MR.REQUEST_NUMBER, TP.THIRDPARTY_NAME
       FROM MDA.THIRDPARTY_REQUEST TR
       LEFT JOIN MDA.MW_VESSEL_INSPECTIONS VI ON TR.MW_VESSEL_INSPECTIONS_ID = VI.ID
       LEFT JOIN MDA.MW_INSPECTION_JOBS IJ ON IJ.ID = VI.INSPECTION_JOB_ID
       LEFT JOIN MDA.MW_MAINTENANCE_REQS MR ON MR.ID = TR.MAINTENANCE_REQ_ID
       LEFT JOIN MDA.CPA_VESSELS V ON V.ID = MR.VESSEL_ID
       LEFT JOIN PMIS.L_DEPARTMENT DEP ON DEP.DEPARTMENT_ID = MR.DEPARTMENT_ID
       LEFT JOIN MDA.THIRDPARTY_ASSIGN TA ON TA.THIRDPARTY_REQ_ID = TR.THIRDPARTY_REQ_ID
       LEFT JOIN MDA.L_THIRDPARTY TP ON TP.THIRDPARTY_ID = TA.THIRDPARTY_ID
       WHERE TR.APPROVE_YN = 'Y'
       AND TR.FORWARD_TO_ASW = $emp_id
       AND VI.TP_REQ_YN != 'C'
       ORDER BY TR.INSERT_DATE DESC" ;
        }else if (in_array("MDA_XEN", $role_key)){
            $querys = "SELECT TR.*,IJ.NAME, DEP.DEPARTMENT_NAME, V.NAME VESSEL_NAME, MR.VESSEL_MASTER_ID, VI.JOB_NUMBER INSPECTOR_JOB_NUMBER, MR.REQUEST_NUMBER, TP.THIRDPARTY_NAME
       FROM MDA.THIRDPARTY_REQUEST TR
       LEFT JOIN MDA.MW_VESSEL_INSPECTIONS VI ON TR.MW_VESSEL_INSPECTIONS_ID = VI.ID
       LEFT JOIN MDA.MW_INSPECTION_JOBS IJ ON IJ.ID = VI.INSPECTION_JOB_ID
       LEFT JOIN MDA.MW_MAINTENANCE_REQS MR ON MR.ID = TR.MAINTENANCE_REQ_ID
       LEFT JOIN MDA.CPA_VESSELS V ON V.ID = MR.VESSEL_ID
       LEFT JOIN PMIS.L_DEPARTMENT DEP ON DEP.DEPARTMENT_ID = MR.DEPARTMENT_ID
       LEFT JOIN MDA.THIRDPARTY_ASSIGN TA ON TA.THIRDPARTY_REQ_ID = TR.THIRDPARTY_REQ_ID
       LEFT JOIN MDA.L_THIRDPARTY TP ON TP.THIRDPARTY_ID = TA.THIRDPARTY_ID
       WHERE TR.APPROVE_YN = 'Y'
       AND TR.FORWARD_TO_XEN = $emp_id
       AND VI.TP_REQ_YN != 'C'
       ORDER BY TR.INSERT_DATE DESC" ;
        }else{
            $querys = "SELECT TR.*,IJ.NAME, DEP.DEPARTMENT_NAME, V.NAME VESSEL_NAME, MR.VESSEL_MASTER_ID, VI.JOB_NUMBER INSPECTOR_JOB_NUMBER, MR.REQUEST_NUMBER, TP.THIRDPARTY_NAME
       FROM MDA.THIRDPARTY_REQUEST TR
       LEFT JOIN MDA.MW_VESSEL_INSPECTIONS VI ON TR.MW_VESSEL_INSPECTIONS_ID = VI.ID
       LEFT JOIN MDA.MW_INSPECTION_JOBS IJ ON IJ.ID = VI.INSPECTION_JOB_ID
       LEFT JOIN MDA.MW_MAINTENANCE_REQS MR ON MR.ID = TR.MAINTENANCE_REQ_ID
       LEFT JOIN MDA.CPA_VESSELS V ON V.ID = MR.VESSEL_ID
       LEFT JOIN PMIS.L_DEPARTMENT DEP ON DEP.DEPARTMENT_ID = MR.DEPARTMENT_ID
       LEFT JOIN MDA.THIRDPARTY_ASSIGN TA ON TA.THIRDPARTY_REQ_ID = TR.THIRDPARTY_REQ_ID
       LEFT JOIN MDA.L_THIRDPARTY TP ON TP.THIRDPARTY_ID = TA.THIRDPARTY_ID
       WHERE TR.APPROVE_YN = 'Y'
       AND VI.TP_REQ_YN != 'C'
       ORDER BY TR.INSERT_DATE DESC" ;
        }

        $queryResult = DB::select($querys);

        return datatables()->of($queryResult)
            ->addColumn('request_date', function ($query) {
                if ($query->request_date == null) {
                    return '--';

                } else {
                    return Carbon::parse($query->request_date)->format('d-m-Y h:i A');
                }
            })
            ->addColumn('approve_yn', function ($query) {
                if($query->approve_yn=="Y"){
                    $html = <<<HTML
<span class="badge badge-success"><b> TASK MONITORING IN PROGRESS</b></span>
HTML;
                    return $html;
                }else if($query->approve_yn=="R"){
                    $html = <<<HTML
<span class="badge badge-danger"><b> REJECTED</b></span>
HTML;
                    return $html;
                }else if($query->approve_yn=="C"){
                    $html = <<<HTML
<span class="badge badge-light"><b> COMPLETED</b></span>
HTML;
                    return $html;
                }else{
                    $html = <<<HTML
<span class="badge badge-circle-light-dark"><b> PENDING</b></span>
HTML;
                    return $html;
                }

            })
            ->addColumn('action', function ($query) {
                if($query->approve_yn=='Y'){
                    $actionBtn = '<a title="Monitor Tasks" href="' . route('mwe.operation.third-party-tasks-monitor', [$query->thirdparty_req_id]) . '"><i class="bx bx-edit cursor-pointer" style="color: #0E9A00;"></i></a>';
                    return $actionBtn;
                }

            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function taskMonitor($id)
    {
        $querys = "select ttm.* from MDA.THIRDPARTY_REQUEST ttm
where ttm.THIRDPARTY_REQ_ID = :THIRDPARTY_REQ_ID" ;
        $data = db::selectOne($querys,['THIRDPARTY_REQ_ID' => $id]);

        $querys = "SELECT COUNT(*) TOTAL FROM MDA.THIRDPARTY_TASK_MONITOR
WHERE THIRDPARTY_REQ_ID = :THIRDPARTY_REQ_ID
AND TASK_STATUS_ID <>4 AND TASK_STATUS_ID <>3";
        $queryResult = db::selectOne($querys,['THIRDPARTY_REQ_ID' => $id]);

        $querys = "SELECT * FROM MDA.THIRDPARTY_TASK_MONITOR
WHERE THIRDPARTY_REQ_ID = :THIRDPARTY_REQ_ID";
        $taskDetail = db::select($querys,['THIRDPARTY_REQ_ID' => $id]);


        return view('mwe.thirdparty.thirdparty_tasks_dtl', [
            'thirdparty_req_id' => $id,
            'maintenance_req_id' => $data->maintenance_req_id,
            'total' => $queryResult->total,
            'tasks' => $taskDetail,
        ]);
    }

    public function monitordataTableList(Request $request)
    {
        /*$maintenance_req_id = $request->get("maintenance_req_id");
        $querys = "select ttm.*, tts.TASK_STATUS from MDA.THIRDPARTY_TASK_MONITOR ttm
left join MDA.L_THIRDPARY_TASK_STATUS tts on tts.TASK_STATUS_ID = ttm.TASK_STATUS_ID
where ttm.MAINTENANCE_REQ_ID = :MAINTENANCE_REQ_ID";
        $queryResult = db::select($querys,['MAINTENANCE_REQ_ID' => $maintenance_req_id]);*/
        $thirdparty_req_id = $request->get("thirdparty_req_id");
        $querys = "select ttm.*, tts.TASK_STATUS from MDA.THIRDPARTY_TASK_MONITOR ttm
left join MDA.L_THIRDPARY_TASK_STATUS tts on tts.TASK_STATUS_ID = ttm.TASK_STATUS_ID
where ttm.THIRDPARTY_REQ_ID = :THIRDPARTY_REQ_ID";
        $queryResult = db::select($querys,['THIRDPARTY_REQ_ID' => $thirdparty_req_id]);
        return datatables()->of($queryResult)
            ->addColumn('estimated_date', function ($data) {
                if ($data->estimated_date!=null){
                    return Carbon::parse($data->estimated_date)->format('d-m-Y');
                }else{
                    return '--';
                }

            })
            ->addColumn('completed_date', function ($data) {
                if ($data->completed_date!=null){
                    return Carbon::parse($data->completed_date)->format('d-m-Y');
                }else{
                    return '--';
                }
            })
            ->addColumn('estimated_date_ch', function ($data) {
                $html = <<<HTML
<div class="row"><div class="col">
<input type="text" onclick="call_date_picker(this)" autocomplete="off" class="form-control datetimepicker-input"
    id="estimated_date_$data->task_monitor_id" data-target="#estimated_date_$data->task_monitor_id"
    data-toggle="datetimepicker" name="estimated_date[]"
/></div></div>
HTML;
                return $html;
            })
            ->addColumn('completed_date_ch', function ($data) {
                $html = <<<HTML
<div class="row"><div class="col">
<input type="text" onclick="call_date_picker(this)" autocomplete="off" class="form-control datetimepicker-input"
    id="completed_date_$data->task_monitor_id" data-target="#completed_date_$data->task_monitor_id"
    data-toggle="datetimepicker" name="completed_date[]"
/></div></div>
HTML;
                return $html;
            })
            ->addColumn('task_status_id_ch', function ($data) {
                $html = <<<HTML
<input type="hidden" name="task_monitor_id[]" value="{$data->task_monitor_id}" />
<select name="task_status_id[]"  class="custom-select select2 form-control task_status" autocomplete="off"><option value="0">Select One</option><option value="1">PENDING</option><option value="2">IN PROGRESS</option><option value="3">COMPLETED</option><option value="4">NOT POSSIBLE</option></select>
HTML;
                return $html;
            })
            ->addColumn('action', function ($data) {
                    $actionBtn = '<a title="Delete" data-taskmonitorid="'.$data->task_monitor_id.'" class="dltBtn"><i class="bx bx-trash cursor-pointer" style="color: #CC0033;"></i></a>';
                    return $actionBtn;
            })
            ->rawColumns(['completed_date_ch', 'estimated_date_ch','task_status_id_ch'])
            ->addIndexColumn()
            ->escapeColumns([])
            ->make(true);

    }

    public function changeInfo(Request $request)
    {//dd($request->all());
        $est_com_date = $request->get('est_com_date');
        $status = $request->get('status');
        $completed_date = $request->get('completed_date');

        if($completed_date!=''){
            $completed_date = date('Y-m-d', strtotime($completed_date));
        }

        if($est_com_date!=''){
            $est_com_date = date('Y-m-d', strtotime($est_com_date));
        }

        DB::beginTransaction();
        if($status==0 || $status ==1){
            $result = DB::table('mda.thirdparty_task_monitor')
                ->where('task_monitor_id', $request->get("task_monitor_id"))
                ->update(['task_status_id' => $status, 'estimated_date'=> '', 'completed_date'=> '']);
        }else if($status==2){
            $result = DB::table('mda.thirdparty_task_monitor')
                ->where('task_monitor_id', $request->get("task_monitor_id"))
                ->update(['task_status_id' => $status, 'estimated_date'=> $est_com_date, 'completed_date'=> '']);
        }else if($status==3){
            $result = DB::table('mda.thirdparty_task_monitor')
                ->where('task_monitor_id', $request->get("task_monitor_id"))
                ->update(['task_status_id' => $status, 'completed_date'=> $completed_date]);
        }else if($status==4){
            $result = DB::table('mda.thirdparty_task_monitor')
                ->where('task_monitor_id', $request->get("task_monitor_id"))
                ->update(['task_status_id' => $status, 'estimated_date'=> '', 'completed_date'=> '']);
        }
        DB::commit();
        if($result=='1'){
            $status_code = $result;
            $status_message = 'Updated Successfully.';
            $lastParams = [
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message
            ];
        }else{
            $status_code = 0;
            $status_message = 'Something went wrong..';
            $lastParams = [
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message
            ];
        }

        $lastParams['html'] = view('mwe.thirdparty.message')->with('params', $lastParams)->render();
        return $lastParams;
    }

    public function getMonitorData(Request $request)
    {
        $querys = "SELECT TM.*, TS.TASK_STATUS FROM MDA.THIRDPARTY_TASK_MONITOR TM
LEFT JOIN MDA.L_THIRDPARY_TASK_STATUS TS ON TS.TASK_STATUS_ID = TM.TASK_STATUS_ID
WHERE TM.TASK_MONITOR_ID = :TASK_MONITOR_ID" ;
        $result = db::selectOne($querys,['TASK_MONITOR_ID' => $request->get("task_monitor_id")]);
        return  response(
            [
                'result' => $result,
            ]
        );
    }

    public function addNewDtl(Request $request)
    {//dd($request->all());

        $lastParams = [];
        DB::beginTransaction();

        try {
                $id = null;
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params = [
                    "P_TASK_MONITOR_ID" => $id,
                    "P_DESCRIPTION" => $request->get("description"),
                    "P_ASSIGN_ID" => $request->get("assign_id"),
                    "P_TASK_STATUS_ID" => '1',
                    "P_ESTIMATED_DATE" => null,
                    "P_COMPLETED_DATE" => null,
                    "P_THIRDPARTY_REQ_ID" => $request->get("thirdparty_req_id"),
                    "P_INSERT_BY" => auth()->id(),
                    "o_status_code" => &$status_code,
                    "o_status_message" => &$status_message
                ];
                DB::executeProcedure("MDA.THIRDPARTY_SERVICE.THIRDPARTY_TASK_MONITORING_INS", $params);

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

    /*private function ins(Request $request)
    {//dd($request);
        try {
            DB::beginTransaction();
            if($request->get("tab_description")){
                foreach ($request->get("tab_description") as $indx => $value) {
                    $id = null;
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params = [
                        "P_TASK_MONITOR_ID" => $id,
                        "P_DESCRIPTION" => $request->get("tab_description")[$indx],
                        "P_ASSIGN_ID" => $request->get("assign_id"),
                        "P_TASK_STATUS_ID" => '1',
                        "P_ESTIMATED_DATE" => null,
                        "P_COMPLETED_DATE" => null,
                        "P_THIRDPARTY_REQ_ID" => $request->get("thirdparty_req_id"),
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];
                    DB::executeProcedure("MDA.THIRDPARTY_SERVICE.THIRDPARTY_TASK_MONITORING_INS", $params);

                    if ($params['o_status_code'] != 1) {
                        DB::rollBack();
                        return $params;
                    }
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }*/

    public function taskDtlPost(Request $request)
    {
        $lastParams = [];
        DB::beginTransaction();

        try {
            foreach ($request->get('task_monitor_id') as $indx => $value) {
                if($request->get('estimated_date')[$indx]!=null){
                    $estimated_date = isset($request->get('estimated_date')[$indx]) ? date('Y-m-d', strtotime($request->get('estimated_date')[$indx])) : '';
                }else{
                    $estimated_date = '';
                }

                if($request->get('completed_date')[$indx]!=null){
                    $completed_date = isset($request->get('completed_date')[$indx]) ? date('Y-m-d', strtotime($request->get('completed_date')[$indx])) : '';
                }else{
                    $completed_date = '';
                }

                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params = [
                    "P_TASK_MONITOR_ID" => $request->get('task_monitor_id')[$indx],
                    "P_TASK_STATUS_ID" => $request->get('task_status_id')[$indx],
                    "P_ESTIMATED_DATE" => $estimated_date,
                    "P_COMPLETED_DATE" => $completed_date,
                    "P_INSERT_BY" => auth()->id(),
                    "o_status_code" => &$status_code,
                    "o_status_message" => &$status_message
                ];//dd($params);

                DB::executeProcedure("MDA.THIRDPARTY_SERVICE.THIRDPARTY_TASK_MONITORING_UPD", $params);

                $lastParams = $params;

                if ($params['o_status_code'] != 1) {
                    DB::rollBack();
                    $params['html'] = view('mwe.thirdparty.message')->with('params', $params)->render();
                }

                DB::commit();
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            return ["exception" => true, "o_status_code" => false, "o_status_message" => $exception->getMessage()];
        }

        $lastParams['html'] = view('mwe.thirdparty.message')->with('params', $lastParams)->render();
        return $lastParams;
    }

    public function ins_upd($thirdparty_req_id)
    {
        $querys = "select MAINTENANCE_REQ_ID, MW_VESSEL_INSPECTIONS_ID from MDA.THIRDPARTY_REQUEST
where THIRDPARTY_REQ_ID = :THIRDPARTY_REQ_ID" ;
        $data = db::selectOne($querys,['THIRDPARTY_REQ_ID' => $thirdparty_req_id]);

        try {
            $id = null;
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_MAINTENANCE_REQ_ID" => $data->maintenance_req_id,
                "P_MW_VESSEL_INSPECTIONS_ID" => $data->mw_vessel_inspections_id,
                "P_UPDATED_BY" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message
            ];//dd($params);
            DB::executeProcedure("MDA.THIRDPARTY_SERVICE.MW_WORKSHOP_REQ_PROCESS", $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return ["exception" => true, "o_status_code" => false, "o_status_message" => $exception->getMessage()];
        }
        return $params;
    }

    public function taskFinalSubmit($thirdparty_req_id)
    {
        $response = $this->ins_upd($thirdparty_req_id);
        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect('/mwe/third-party-tasks');
    }

    public function removeData(Request $request)
    {//dd($request);
        try {
            ThirdpartyTaskMonitor::where('task_monitor_id', $request->get("task_monitor_id"))->delete();
            return '1';
        } catch (\Exception $e) {
            DB::rollBack();
            return '0';
        }

    }
    public function statusChk(Request $request)
    {
        $thirdparty_req_id = $request->get("thirdparty_req_id");
        $querys = "SELECT COUNT(*) TOTAL FROM MDA.THIRDPARTY_TASK_MONITOR
WHERE THIRDPARTY_REQ_ID = :THIRDPARTY_REQ_ID
AND TASK_STATUS_ID <>4 AND TASK_STATUS_ID <>3";
        $queryResult = db::selectOne($querys,['THIRDPARTY_REQ_ID' => $thirdparty_req_id]);
        return $queryResult->total;
    }
}
