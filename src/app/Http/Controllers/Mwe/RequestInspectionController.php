<?php

namespace App\Http\Controllers\Mwe;

use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Entities\Mwe\InspectionJob;
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
use Yajra\DataTables\Facades\DataTables;

class RequestInspectionController extends Controller
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
        return view('mwe.maintenance_inspection_req_list');
    }



    public function makeInspectionReport($id)
    {
        return view('mwe.maintenance_inspection_req',
            ['maintenanceReqData' => MaintenanceReq::where('id',$id)->with('vessel','department','vesselMaster','assignedInspector')->first(),
             'inspection'=>$this->inspectionReqManager->getInspection($id),
             'slipways'=>Slipway::where('status', '=','A')->orderBy('name', 'ASC')->get(),
             'inspectionJobs'=>InspectionJob::all()->sortBy('name'),
              'workshops'=>Workshop::where('status', '=','A')->orderBy('name', 'ASC')->get(),
              'data'=>$this->inspectionReqManager->getMaintenanceInspector($id),
            ]);
    }

    public function addInspectionJob(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'inspection_job_id' => 'required',
            ]);
            $managerRes = $this->inspectionReqManager->maintenanceInspectionJob('I',$request);
            $workshops=Workshop::where('status', '=','A')->orderBy('name', 'ASC');
            $view = view('mwe.partial.inspection_jobs', [
                'maintenanceReqData' => MaintenanceReq::where('id',$request->get("maintenance_req_id"))->with('vessel','department','vesselMaster','assignedInspector')->first(),
                'data'=>$this->inspectionReqManager->getVesselInspection($request->get("maintenance_req_id")),
                'workshops'=>$workshops
            ])->render();
            return response()->json(['html' => $view,'response'=>$managerRes]);
        }

    }

    public function addVesselInspector(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'assigned_ssae_emp_id' => 'required',
                'assigned_sae_emp_id' => 'required',
            ]);
            $managerRes = $this->inspectionReqManager->maintenanceVesselInspector('I',$request);
            $view = view('mwe.partial.maintenance_inspectors', [
                'maintenanceReqData' => MaintenanceReq::where('id',$request->get("maintenance_req_id"))->with('vessel','department','vesselMaster','assignedInspector')->first(),
                'data'=>$this->inspectionReqManager->getMaintenanceInspector($request->get("maintenance_req_id")),
            ])->render();
            return response()->json(['html' => $view,'response'=>$managerRes]);
        }

    }

    public function removeVesselInspector(Request $request)
    {

        $managerRes = $this->inspectionReqManager->maintenanceVesselInspector('D',$request,$request->get('inspector_job_id'));
        $view = view('mwe.partial.maintenance_inspectors', [
            'maintenanceReqData' => MaintenanceReq::where('id',$request->get("maintenance_req_id"))->with('vessel','department','vesselMaster','assignedInspector')->first(),
            'data'=>$this->inspectionReqManager->getMaintenanceInspector($request->get("maintenance_req_id")),
        ])->render();
        return response()->json(['html' => $view,'response'=>$managerRes]);

    }


    public function viewInspectionByInspectorJob($id){

        $view = view('mwe.partial.inspection_view_by_inspector_job', [
            'data'=>$this->inspectionReqManager->getInspectionByInspectorJob($id),
        ])->render();
        return response()->json(['html' => $view]);
    }

    public function getInspectionJob($id){
        $workshops=Workshop::all()->sortBy('name');
        $view = view('mwe.partial.inspection_jobs', [
            'data'=>$this->inspectionReqManager->getVesselInspection($id),
            'workshops'=>$workshops
        ])->render();
        return response()->json(['html' => $view]);
    }

    public function removeInspectionByInspector(Request $request)
    {//dd($request->all());
        /*$managerRes = $this->inspectionReqManager->maintenanceInspectionJob('D',$request,$id);
        $workshops=Workshop::where('status', '=','A')->orderBy('name', 'ASC');
        $view = view('mwe.partial.inspection_jobs', [
            'maintenanceReqData' => MaintenanceReq::where('id',$request->get("maintenance_req_id"))->with('vessel','department','vesselMaster','assignedInspector')->first(),
            'data'=>$this->inspectionReqManager->getVesselInspection($request->get("maintenance_req_id")),
            'workshops'=>$workshops
        ])->render();
        return response()->json(['html' => $view,'response'=>$managerRes]);*/
        $notify = $this->inspectionReqManager->notifyReInspect('I',$request, $request->get('job_dtl_id'));
        $managerRes = $this->inspectionReqManager->storeInspectionJob('D',$request, $request->get('job_dtl_id'));//dd($managerRes);

        $view = view('mwe.partial.inspection_view_by_inspector_job', [
            'data'=>$this->inspectionReqManager->getInspectionByInspectorJob($request->get('inspector_job_id')),
        ])->render();
        return response()->json(['html' => $view,'response'=>$managerRes]);

    }

    public function removeInspectionJob(Request $request,$id)
    {
        $managerRes = $this->inspectionReqManager->maintenanceInspectionJob('D',$request,$id);
        $workshops=Workshop::where('status', '=','A')->orderBy('name', 'ASC');
        $view = view('mwe.partial.inspection_jobs', [
            'maintenanceReqData' => MaintenanceReq::where('id',$request->get("maintenance_req_id"))->with('vessel','department','vesselMaster','assignedInspector')->first(),
            'data'=>$this->inspectionReqManager->getVesselInspection($request->get("maintenance_req_id")),
            'workshops'=>$workshops
        ])->render();
        return response()->json(['html' => $view,'response'=>$managerRes]);

    }

    public function store(Request $request,$id)
    {//dd($request->all());
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
        $dataTable = $this->inspectionReqManager->inspectionDatatable();
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
                    $optionHtml='';
                    if($data['status']==4){
                        $optionHtml = ' <a class="btn btn-primary btn-sm" href="' . route('mwe.operation.request-inspection-report', $data['id']) . '">Inspection</a>';
                    }elseif($data['status']==5 ||$data['status']==7){
                        $optionHtml = ' <a class="btn btn-primary btn-sm" href="' . route('mwe.operation.request-inspection-report', $data['id']) . '">Approved Inspection</a>';
                    }elseif ($data['status']==6){
                        $optionHtml = ' <a class="btn btn-primary btn-sm" href="' . route('mwe.operation.request-inspection-report', $data['id']) . '">Assign Slipway</a>';
                    }elseif ($data['status']==8){
                        $optionHtml = ' <a class="btn btn-primary btn-sm" href="' . route('mwe.operation.request-inspection-report', $data['id']) . '">Assign Workshop</a>';
                    }else{
                        $optionHtml = ' <a class="btn btn-primary btn-sm" href="' . route('mwe.operation.request-inspection-report', $data['id']) . '">Inspection</a>';
                    }
                    return $optionHtml;
                }
            })

            ->editColumn('status', function ($data) {
                if(isset($data['status']) && !empty($data['status'])){
                    $status = HelperClass::getReqStatus($data['status']);
//                    $res ='<span style="color: '.$status->color_code.'">';
                    $res = '<span  class="badge badge-pill" style="background-color:'.$status->color_code.'">';
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

}
