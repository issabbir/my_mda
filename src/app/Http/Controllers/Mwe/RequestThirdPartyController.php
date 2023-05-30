<?php

namespace App\Http\Controllers\Mwe;

use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Entities\Mwe\MaintenanceReq;
use App\Entities\Mwe\ThirdpartyRequest;
use App\Entities\Mwe\Unit;
use App\Entities\Mwe\Works;
use App\Entities\Mwe\Workshop;
use App\Http\Controllers\Controller;
use App\Managers\Mwe\InspectionReqManager;
use App\Managers\Mwe\WorkshopManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RequestThirdPartyController extends Controller
{
    public $employeeManager;
    public $inspectionReqManager;
    public $workshopManager;

    public function __construct(EmployeeContract $employeeManager,InspectionReqManager $inspectionReqManager,WorkshopManager $workshopManager)
    {
        $this->employeeManager = $employeeManager;
        $this->inspectionReqManager = $inspectionReqManager;
        $this->workshopManager = $workshopManager;
    }

    public function index($maintenance_req_id, $workshopId, $vessel_inspection_id)
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

        $sql = "SELECT VI.*,
       IJ.NAME,
       IR.REQUISITION_NUMBER,
       MI.INSPECTOR_JOB_NUMBER,
       IR.STATUS
  FROM MDA.MW_VESSEL_INSPECTIONS  VI
       LEFT JOIN MDA.MW_INSPECTION_REQUISITIONS IR
           ON IR.VESSEL_INSPECTION_ID = VI.ID
       LEFT JOIN MDA.MW_INSPECTION_JOBS IJ ON IJ.ID = VI.INSPECTION_JOB_ID
       LEFT JOIN MDA.MW_MAINTENANCE_INSPECTOR MI
           ON MI.MAINTENANCE_REQ_ID = VI.MAINTENANCE_REQ_ID
 WHERE     VI.WORKSHOP_ID = :WORKSHOP_ID
       AND VI.ID = :VESSEL_INSPECTION_ID
       AND VI.MAINTENANCE_REQ_ID = :MAINTENANCE_REQ_ID
       AND ROWNUM = 1";
        $taskDtl = db::selectOne($sql,['workshop_id' => $workshopId, 'vessel_inspection_id' => $vessel_inspection_id, 'maintenance_req_id' => $maintenance_req_id]);

        return view('mwe.thirdparty.thirdparty_requisition',
            ['maintenanceReqData' => MaintenanceReq::where('id', $maintenance_req_id)->with('vessel', 'department', 'vesselMaster', 'assignedInspector')->first(),
                'workshop' => Workshop::where('id', $workshopId)->first(),
                'task' => $taskDtl,
                'thirdpartyreq' => ThirdpartyRequest::where('maintenance_req_id', $maintenance_req_id)
                    ->where('mw_vessel_inspections_id', $vessel_inspection_id)
                    ->where('mw_workshop_id', $workshopId)
                    ->first(),
                'maintenance_req_id' => $maintenance_req_id,
                'workshopId' => $workshopId,
                'vessel_inspection_id' => $vessel_inspection_id,
                'send_to' => $users,
                'role' => $role,
                'list_of_works' => Works::where('active_yn','!=', 'D')->get(),
            ]);
    }

    public function post(Request $request)
    {
        $response = $this->ins_upd($request);
        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        //return redirect('/mwe/third-party-assign/' . $request->get('maintenance_req_id').'/'. $request->get('workshopId').'/'. $request->get('vessel_inspection_id'));
        return redirect('/mwe/workshop-requisition/' . $request->get('maintenance_req_id').'/'. $request->get('workshopId'));
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
                "P_INSERT_BY" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message
            ];
            DB::executeProcedure("MDA.THIRDPARTY_SERVICE.TASK_MONITORING_INS", $params);

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

    public function monitordataTableList(Request $request)
    {
        $maintenance_req_id = $request->get("maintenance_req_id");
        $workshopId = $request->get("workshopId");
        $vessel_inspection_id = $request->get("vessel_inspection_id");
        $querys = "select ttm.*, tts.TASK_STATUS from MDA.THIRDPARTY_TASK_MONITOR ttm
left join MDA.L_THIRDPARY_TASK_STATUS tts on tts.TASK_STATUS_ID = ttm.TASK_STATUS_ID
where ttm.MAINTENANCE_REQ_ID = :MAINTENANCE_REQ_ID
AND ttm.MW_VESSEL_INSPECTIONS_ID = :MW_VESSEL_INSPECTIONS_ID
AND ttm.MW_WORKSHOP_ID = :MW_WORKSHOP_ID
order by ttm.INSERT_DATE DESC" ;
        $queryResult = db::select($querys,['MW_WORKSHOP_ID' => $workshopId, 'MW_VESSEL_INSPECTIONS_ID' => $vessel_inspection_id, 'MAINTENANCE_REQ_ID' => $maintenance_req_id]);
        return datatables()->of($queryResult)
            ->addColumn('action', function ($data) {
                $actionBtn = '<a title="Delete" data-taskmonitorid="'.$data->task_monitor_id.'" class="dltBtn"><i class="bx bx-trash cursor-pointer" style="color: #CC0033;"></i></a>';
                return $actionBtn;
            })
            ->addColumn('description', function ($data) {
                if(!empty($data->description)){
                    $pieces = explode(" ", $data->description);
                    $first_line = implode(" ", array_splice($pieces, 0, 12));
                    $show_line = $first_line.'....';
                }else{
                    $show_line = '';
                }
                return $show_line;
            })
            ->rawColumns(['completed_date_ch', 'estimated_date_ch','task_status_id_ch'])
            ->addIndexColumn()
            ->escapeColumns([])
            ->make(true);
    }

    private function ins_upd(Request $request)
    {//dd($request);
        $postData = $request->post();
        /*$data = $postData['forward_to'];
        $tmp = explode('_', $data);
        $emp_id = $tmp[0];//dd($emp_id);
        $role = substr($data, strpos($data, "_") + 1);//dd($role);*/
        $thirdparty_req_id = null;
        $today = date("Y-m-d");

        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'P_THIRDPARTY_REQ_ID' => [
                    'value' => &$thirdparty_req_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'P_MAINTENANCE_REQ_ID' => $postData['maintenance_req_id'],
                'P_MW_VESSEL_INSPECTIONS_ID' => $postData['vessel_inspection_id'],
                'P_REMARKS' => '',//$postData['remarks'],
                'P_REQUEST_DATE' => $today,
                'P_MW_WORKSHOP_ID' => $postData['workshopId'],
                'p_FORWARD_TO_SSAE' => $postData['forward_to'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('MDA.THIRDPARTY_SERVICE.THIRDPARTY_REQUEST_ENTRY', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }

}
