<?php

namespace App\Http\Controllers\Mwe;

use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Entities\Mwe\InspectionJob;
use App\Entities\Mwe\MaintenanceInspectionJob;
use App\Entities\Mwe\MaintenanceInspector;
use App\Entities\Mwe\MaintenanceReq;
use App\Entities\Mwe\Slipway;
use App\Entities\Mwe\Workshop;
use App\Enums\Mwe\ConfigRole;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Mwe\InspectionReqManager;
use App\Managers\Pmis\Employee\EmployeeManager;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InspectionController extends Controller
{
    public $employeeManager;
    public $inspectionReqManager;

    public function __construct(EmployeeContract $employeeManager,InspectionReqManager $inspectionReqManager)
    {
        $this->employeeManager = $employeeManager;
        $this->inspectionReqManager = $inspectionReqManager;
    }

    public function index()
    {
        return view('mwe.inspection_order_list');
    }

    public function view($id)
    {
        $getKey = json_encode(Auth::user()->roles->pluck('role_key'));//dd($getKey);

        if (strpos($getKey, "MDA_SSAE_M") == TRUE){
            $btn_shw_data = MaintenanceInspector::where('maintenance_req_id', $id)
                ->where('ASSIGNED_SSAE_EMP_ID', Auth::user()->emp_id)
                ->first('insp_conf_ssae');
            $btn_shw_data =$btn_shw_data->insp_conf_ssae;
        }else{
            $btn_shw_data = '';
        }

        return view('mwe.inspection_order_view',
            ['maintenanceReqData' => MaintenanceReq::where('id',$id)->with('vessel','department','vesselMaster','assignedInspector')->first(),
             'inspection_order_jobs'=>$this->inspectionReqManager->getInspectionOrderJob($id),
             'inspectionJobs'=>InspectionJob::where('STATUS','A')->get(),//InspectionJob::all()->sortBy('name'),
             'data'=>$this->inspectionReqManager->getMaintenanceInspection($id),
             'ssae_conf'=>$btn_shw_data,
            ]);
    }

    public function addInspectionJob(Request $request)
    {//dd($request->all());

        if ($request->isMethod("POST")) {
            $request->validate([
                'inspector_job_id' => 'required',
                'inspection_job_type_id' => 'required',
            ]);
            $managerRes = $this->inspectionReqManager->storeInspectionJob('I',$request);//dd($managerRes);
            $view = view('mwe.partial.inspection', [
                'maintenanceReqData' => MaintenanceReq::where('id',$request->get("maintenance_req_id"))->with('vessel','department','vesselMaster','assignedInspector')->first(),
                'data'=>$this->inspectionReqManager->getMaintenanceInspection($request->get("maintenance_req_id")),
                'inspection_order_jobs'=>$this->inspectionReqManager->getInspectionOrderJob($request->get("maintenance_req_id")),
            ])->render();
            return response()->json(['html' => $view,'response'=>$managerRes]);
        }

    }

    public function inspectionOrderDownload($id)
    {
        $docData = MaintenanceInspectionJob::find($id);

        if ($docData) {
            if ($docData->attachment && $docData->file_type) {
                $attachment = substr($docData->attachment, strpos($docData->attachment, ",") + 1);
                $content = base64_decode($attachment);//dd($attachment);

                return response()->make($content, 200, [
                    'Content-Type' => $docData->file_type,
                    'Content-Disposition' => 'attachment; filename="' .'INSPECTION_ORDER_'. $id .'.'. $docData->file_type
                ]);
            }
        }
    }

    public function getInspectionJob($id){
        $workshops=Workshop::all()->sortBy('name');
        $view = view('mwe.partial.inspection', [
            'data'=>$this->inspectionReqManager->getMaintenanceInspection($id),
            'workshops'=>$workshops
        ])->render();
        return response()->json(['html' => $view]);
    }

    public function removeInspectionJob(Request $request)
    {
        $managerRes = $this->inspectionReqManager->storeInspectionJob('D',$request,$request->get('job_dtl_id'));
        $view = view('mwe.partial.inspection', [
            'maintenanceReqData' => MaintenanceReq::where('id',$request->get("maintenance_req_id"))->with('vessel','department','vesselMaster','assignedInspector')->first(),
            'data'=>$this->inspectionReqManager->getMaintenanceInspection($request->get("maintenance_req_id")),
            'inspection_order_jobs'=>$this->inspectionReqManager->getInspectionOrderJob($request->get("maintenance_req_id")),
        ])->render();
        return response()->json(['html' => $view,'response'=>$managerRes]);

    }

    public function store(Request $request,$id)
    {
        if ($request->isMethod("POST")) {
            $managerRes = $this->inspectionReqManager->storeInspectionReport('U',$request,$id);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function datatable($id)
    {
        $dataTable = $this->inspectionReqManager->getInspectionOrderDataTable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->editColumn('department', function ($data) {
                return !empty($data['department'])?$data['department']['name']:'';
            })
            ->editColumn('vessel', function ($data) {
                return !empty($data['vessel'])?$data['vessel']['name']:'';
            })
            ->editColumn('vessel_master', function ($data) {
                return !empty($data['vesselMaster'])?$data['vesselMaster']['emp_name']:'';
            })
            ->editColumn('assigned_inspector', function ($data) {
                return !empty($data['assignedInspector'])?$data['assignedInspector']['emp_name']:'';
            })
            ->editColumn('formatted_request_date', function ($data) {
                return !empty($data['created_at'])?date("d-m-Y", strtotime($data['created_at'])):'';
            })
            ->editColumn('formatted_inspector_assigned_date', function ($data) {
                return !empty($data['inspector_assigned_date'])?date("d-m-Y", strtotime($data['inspector_assigned_date'])):'';
            })
            ->addColumn('created_at', function ($data) {
                return Carbon::parse($data->created_at)->format('d-m-Y h:i A');
            })
            ->addColumn('action', function ($data)  {
                if(in_array(HelperClass::getReqCurrentStatus($data['id']),ConfigRole::can_be_make_inspection)){
                        $optionHtml = ' <a class="btn btn-primary btn-sm" href="' . route('mwe.operation.inspection-order-view', $data['id']) . '">Inspection</a>';
                    return $optionHtml;
                }
            })
            ->editColumn('status', function ($data) {
                if(isset($data['status']) && !empty($data['status'])){
                    $status = HelperClass::getReqStatus($data['status']);
//                    $res ='<span style="color: '.$status->color_code.'">';
                    $res = '<span  class="badge badge-pill text-wrap" style="background-color:'.$status->color_code.'">';
                    $res .= $status->name;
                    $res .='</span>';
                }else{
                    $res = '';
                }
                return $res;
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function inspConfirmSSAE(Request $request)
    {
        $managerRes = $this->inspectionReqManager->updateInspConfirmSSAE($request);
        if ($managerRes['status']) {
            $message = redirect()->back()->with('success', $managerRes['status_message']);
        } else {
            $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
        }
        return $message;
    }

    public function getDataFromJob(Request $request)
    {   //dd($request->all());
        $inspector_job_id =$request->get("inspector_job_id");
        $mri = substr($inspector_job_id, 0, 4);
        if($mri == 'MRI+'){
            $role_key = json_decode(Auth::user()->roles->pluck('role_key'));
            $and = '';
            if (in_array("MDA_SAE_M", $role_key)) {
                $and = 'AND MI.ASSIGNED_SAE_EMP_ID = '.auth()->user()->employee->emp_id;
            }else if (in_array("MDA_SSAE_M", $role_key)){
                $and = 'AND MI.ASSIGNED_SSAE_EMP_ID = '.auth()->user()->employee->emp_id;
            }


            $whatIWant = substr($inspector_job_id, strpos($inspector_job_id, "+") + 1);
           /* $querys = "SELECT MIJD.*, IJ.NAME, MI.INSP_CONF_SSAE
    FROM MDA.MW_MAIN_INSPECTION_JOB_DTL MIJD
         LEFT JOIN MDA.MW_INSPECTION_JOBS IJ
             ON IJ.ID = MIJD.INSPECTION_JOB_TYPE_ID
         LEFT JOIN MDA.MW_MAINTENANCE_INSPECTOR MI
             ON MI.INSPECTOR_JOB_ID = MIJD.INSPECTOR_JOB_ID
   WHERE     MI.MAINTENANCE_REQ_ID = :MAINTENANCE_REQ_ID
   $and
         AND MIJD.STATUS IN ('A', 'P')
ORDER BY MIJD.CREATED_AT DESC" ;*/
            $querys = "SELECT MIJD.*, IJ.NAME, MI.INSP_CONF_SSAE, CASE WHEN SU.EMP_ID = :CREATED_BY THEN 'Y' ELSE 'N' END  CREATED_BY_INSPACTION
    FROM MDA.MW_MAIN_INSPECTION_JOB_DTL MIJD
         LEFT JOIN MDA.MW_INSPECTION_JOBS IJ
             ON IJ.ID = MIJD.INSPECTION_JOB_TYPE_ID
         LEFT JOIN MDA.MW_MAINTENANCE_INSPECTOR MI
             ON MI.INSPECTOR_JOB_ID = MIJD.INSPECTOR_JOB_ID
             LEFT JOIN CPA_SECURITY.SEC_USERS SU
             ON SU.USER_ID = MIJD.CREATED_BY
   WHERE     MI.MAINTENANCE_REQ_ID = :MAINTENANCE_REQ_ID
   $and
         AND MIJD.STATUS IN ('A', 'P')
ORDER BY MIJD.CREATED_AT DESC" ;
            $job_data = db::select($querys,['MAINTENANCE_REQ_ID' => $whatIWant, 'CREATED_BY' => auth()->user()->employee->emp_id]);
        }else{
            $querys = "SELECT MIJD.*, IJ.NAME, MI.INSP_CONF_SSAE, CASE WHEN SU.EMP_ID = :CREATED_BY THEN 'Y' ELSE 'N' END  CREATED_BY_INSPACTION
    FROM MDA.MW_MAIN_INSPECTION_JOB_DTL MIJD
         LEFT JOIN MDA.MW_INSPECTION_JOBS IJ
             ON IJ.ID = MIJD.INSPECTION_JOB_TYPE_ID
         LEFT JOIN MDA.MW_MAINTENANCE_INSPECTOR MI
             ON MI.INSPECTOR_JOB_ID = MIJD.INSPECTOR_JOB_ID
             LEFT JOIN CPA_SECURITY.SEC_USERS SU
             ON SU.USER_ID = MIJD.CREATED_BY
   WHERE     MIJD.INSPECTOR_JOB_ID = :INSPECTOR_JOB_ID
         AND MIJD.STATUS IN ('A', 'P')
ORDER BY MIJD.CREATED_AT DESC" ;
            $job_data = db::select($querys,['INSPECTOR_JOB_ID' => $request->get("inspector_job_id")]);
        }

        return  response(
            [
                'job_data' => $job_data,
            ]
        );
    }
}
