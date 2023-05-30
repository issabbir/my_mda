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
use Illuminate\Support\Facades\DB;
use PDO;
use Yajra\DataTables\Facades\DataTables;

class ThirdPartyRequestsController extends Controller
{
    public function index()
    {
        return view('mwe.thirdparty.thirdparty_requests');
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
        }

        $querys = "SELECT TA.FORWARD_TO_SAE, TA.FORWARD_TO_SSAE, TA.FORWARD_TO_ASW, TA.FORWARD_TO_XEN, TA.APPROVE_YN, TR.THIRDPARTY_REQ_ID,TR.REQUEST_DATE,IJ.NAME TASK, DEP.DEPARTMENT_NAME, V.NAME VESSEL_NAME, MI.INSPECTOR_JOB_NUMBER, MR.REQUEST_NUMBER, TP.THIRDPARTY_NAME FROM MDA.THIRDPARTY_REQUEST TR LEFT JOIN MDA.MW_VESSEL_INSPECTIONS VI ON TR.MW_VESSEL_INSPECTIONS_ID = VI.ID LEFT JOIN MDA.MW_INSPECTION_JOBS IJ ON IJ.ID = VI.INSPECTION_JOB_ID LEFT JOIN MDA.MW_MAINTENANCE_REQS MR ON MR.ID = TR.MAINTENANCE_REQ_ID LEFT JOIN MDA.VESSELS V ON V.ID = MR.VESSEL_ID LEFT JOIN PMIS.L_DEPARTMENT DEP ON DEP.DEPARTMENT_ID = MR.DEPARTMENT_ID LEFT JOIN MDA.MW_MAINTENANCE_INSPECTOR MI ON MI.MAINTENANCE_REQ_ID = TR.MAINTENANCE_REQ_ID LEFT JOIN MDA.THIRDPARTY_ASSIGN TA ON TA.THIRDPARTY_REQ_ID = TR.THIRDPARTY_REQ_ID LEFT JOIN MDA.L_THIRDPARTY TP ON TP.THIRDPARTY_ID = TA.THIRDPARTY_ID $where ORDER BY TR.INSERT_DATE DESC" ;
        $queryResult = DB::select($querys);*/


        $querys = "SELECT TR.*,IJ.NAME, DEP.DEPARTMENT_NAME, V.NAME VESSEL_NAME, MR.VESSEL_MASTER_ID, MI.INSPECTOR_JOB_NUMBER, MR.REQUEST_NUMBER, TP.THIRDPARTY_NAME
       FROM MDA.THIRDPARTY_REQUEST TR
       LEFT JOIN MDA.MW_VESSEL_INSPECTIONS VI ON TR.MW_VESSEL_INSPECTIONS_ID = VI.ID
       LEFT JOIN MDA.MW_INSPECTION_JOBS IJ ON IJ.ID = VI.INSPECTION_JOB_ID
       LEFT JOIN MDA.MW_MAINTENANCE_REQS MR ON MR.ID = TR.MAINTENANCE_REQ_ID
       LEFT JOIN MDA.CPA_VESSELS V ON V.ID = MR.VESSEL_ID
       LEFT JOIN PMIS.L_DEPARTMENT DEP ON DEP.DEPARTMENT_ID = MR.DEPARTMENT_ID
       LEFT JOIN MDA.MW_MAINTENANCE_INSPECTOR MI ON MI.MAINTENANCE_REQ_ID = TR.MAINTENANCE_REQ_ID
       LEFT JOIN MDA.THIRDPARTY_ASSIGN TA ON TA.THIRDPARTY_REQ_ID = TR.THIRDPARTY_REQ_ID
       LEFT JOIN MDA.L_THIRDPARTY TP ON TP.THIRDPARTY_ID = TA.THIRDPARTY_ID
       ORDER BY TR.INSERT_DATE DESC" ;
        $queryResult = DB::select($querys);

        return datatables()->of($queryResult)
            ->addColumn('request_date', function ($query) {
                if ($query->request_date == null) {
                    return '--';

                } else {
                    return Carbon::parse($query->request_date)->format('d-m-Y');
                }
            })
            ->addColumn('approve_yn', function ($query) {
                if($query->approve_yn=="Y"){
                    $html = <<<HTML
<span class="badge badge-success"><b> ASSIGNED</b></span>
HTML;
                    return $html;
                }else if($query->approve_yn=="C"){
                    $html = <<<HTML
<span class="badge badge-light"><b> COMPLETED</b></span>
HTML;
                    return $html;
                }else if($query->approve_yn=="R"){
                    $html = <<<HTML
<span class="badge badge-danger"><b> REJECTED</b></span>
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
                if($query->approve_yn=="Y"){
                    $actionBtn = '<a title="Update" href="' . route('mwe.operation.third-party-req-assign', [$query->thirdparty_req_id]) . '"><i class="bx bx-edit cursor-pointer" style="color: #0E9A00"></i></a>';
                    return $actionBtn;
                }
                else if($query->approve_yn!='R' && $query->approve_yn!='C'){
                    $actionBtn = '<a title="Approve" href="' . route('mwe.operation.third-party-req-assign', [$query->thirdparty_req_id]) . '"><i class="bx bxs-check-circle cursor-pointer" style="color: #0E9A00"></i></a>'.' || '
                        .'<a title="Decline" data-thirdpartyreqid="'.$query->thirdparty_req_id.'" href="javascript:void(0)" class="show-receive-modal rejectBtn"><i style="color: #CC0033" class="bx bxs-x-circle cursor-pointer"></i></a>';
                    return $actionBtn;
                }

            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function assignThirdparty($id)
    {
        $sql = "SELECT TR.*,IJ.NAME, DEP.DEPARTMENT_NAME, V.NAME VESSEL_NAME, MR.VESSEL_MASTER_ID, MI.INSPECTOR_JOB_NUMBER, MR.REQUEST_NUMBER
       FROM MDA.THIRDPARTY_REQUEST TR
       LEFT JOIN MDA.MW_VESSEL_INSPECTIONS VI ON TR.MW_VESSEL_INSPECTIONS_ID = VI.ID
       LEFT JOIN MDA.MW_INSPECTION_JOBS IJ ON IJ.ID = VI.INSPECTION_JOB_ID
       LEFT JOIN MDA.MW_MAINTENANCE_REQS MR ON MR.ID = TR.MAINTENANCE_REQ_ID
       LEFT JOIN MDA.VESSELS V ON V.ID = MR.VESSEL_ID
       LEFT JOIN PMIS.L_DEPARTMENT DEP ON DEP.DEPARTMENT_ID = MR.DEPARTMENT_ID
       LEFT JOIN MDA.MW_MAINTENANCE_INSPECTOR MI ON MI.MAINTENANCE_REQ_ID = TR.MAINTENANCE_REQ_ID
       WHERE TR.THIRDPARTY_REQ_ID = :THIRDPARTY_REQ_ID
       ORDER BY TR.INSERT_DATE DESC";
        $mData = db::selectOne($sql,['THIRDPARTY_REQ_ID' => $id]);

        $sql = "select emp.emp_id,emp.emp_code,emp.emp_name,sr.role_name
from PMIS.EMPLOYEE emp
left join cpa_security.SEC_USERS us on us.EMP_ID = emp.EMP_ID
left join CPA_SECURITY.SEC_USER_ROLES usr on usr.USER_ID = us.USER_ID
left join CPA_SECURITY.SEC_ROLE sr on sr.ROLE_ID = usr.ROLE_ID
where UPPER(sr.ROLE_KEY) = UPPER(:ROLE_KEY)";
        $users = db::select($sql,['ROLE_KEY' => 'MDA_SAE_M']);

        return view('mwe.thirdparty.thirdparty', [
            'data' => ThirdpartyAssign::where('thirdparty_req_id', $id)->first(),
            'thirdpartyList' => L_Thirdparty::all(),
            'thirdparty_req_id' => $id,
            'mData' => $mData,
            'users' => $users,
        ]);
    }

    public function addNewParty(Request $request)
    {
        $response = $this->ins_upd($request);
        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect('/mwe/third-party-requests/' . $request->get('thirdparty_req_id'));
    }

    private function ins_upd(Request $request)
    {//dd($request);
        $postData = $request->post();
        $thirdparty_id = null;

        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'P_THIRDPARTY_ID' => [
                    'value' => &$thirdparty_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'P_THIRDPARTY_NAME' => $postData['thirdparty_name'],
                'P_THIRDPARTY_NAME_BN' => $postData['thirdparty_name_bn'],
                'P_CONTACT_ADDRESS' => $postData['contact_address'],
                'P_CONTACT_NO' => $postData['contact_no'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('MDA.THIRDPARTY_SERVICE.THIRDPARTY_ENTRY', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            if($request->get("tab_doc")){
                $ref_id = $params['P_THIRDPARTY_ID']['value'];
                foreach ($request->get("tab_doc") as $indx => $value) {
                    $id = null;
                    $data = $request->get("tab_doc")[$indx];
                    $doc_content = substr($data, strpos($data, ",") + 1);
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params = [
                        "P_ACTION_TYPE" => 'I',
                        "P_ID" => [
                            'value' => &$id,
                            'type' => \PDO::PARAM_INPUT_OUTPUT,
                            'length' => 255
                        ],
                        "P_FILES" => ['value' => $doc_content, 'type' => PDO::PARAM_LOB],
                        "P_STATUS" => 'A',
                        "P_CREATED_BY" => auth()->id(),
                        "P_UPDATED_BY" => '',
                        "P_SOURCE_TABLE" => 'L_THIRDPARTY',
                        "P_REF_ID" => $ref_id,
                        "P_TITLE" => $request->get("tab_title")[$indx],
                        "P_FILE_TYPE" => $request->get("tab_doc_type")[$indx],
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];//dd($params);
                    DB::executeProcedure("MDA.MDA_CORE_PROCE.MEDIA_FILES_CUD", $params);
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }

    public function assignParty(Request $request)
    {
        $response = $this->ins($request);
        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect('/mwe/third-party-requests');
    }

    private function ins(Request $request)
    {//dd($request);
        $postData = $request->post();
        if($postData['assign_id']!='' && $postData['assign_id']!=null){
            $assign_id = $postData['assign_id'];
            $status = 'U';
        }else{
            $assign_id = null;
            $status = 'I';
        }


        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'P_ASSIGN_ID' => $assign_id,
                'P_THIRDPARTY_REQ_ID' => $postData['thirdparty_req_id'],
                'P_THIRDPARTY_ID' => $postData['thirdparty_id'],
                'P_REMARKS' => $postData['remarks'],
                'p_ACTION_TYPE' => $status,
                'p_FORWARD_TO' => $postData['forward_to_sae'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('MDA.THIRDPARTY_SERVICE.THIRDPARTY_ASSIGNMENT', $params);

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

    public function removeData(Request $request)
    {//dd($request);
        $postData = $request->post();
        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_THIRDPARTY_REQ_ID' => $postData['row_id'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('MDA.THIRDPARTY_SERVICE.TP_REJECT', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params['o_status_code'];
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $params['o_status_code'] = '99';
            return $params['o_status_code'];
            //return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params['o_status_code'];
    }

}
