<?php

namespace App\Http\Controllers\Mwe;

use App\Contracts\Mwe\MaintenanceReqContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Entities\Mda\CpaVessel;
use App\Entities\Mwe\Department;
use App\Entities\Mwe\MaintenanceReq;
use App\Entities\Mwe\Slipway;
use App\Entities\Mwe\Vessel;
use App\Enums\Mwe\ConfigRole;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Pmis\Employee\EmployeeManager;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MaintenanceRequestController extends Controller
{
    public $employeeManager;
    public $maintenanceReqManager;

    public function __construct(EmployeeContract $employeeManager,MaintenanceReqContract $maintenanceReqManager)
    {
        $this->employeeManager = $employeeManager;
        $this->maintenanceReqManager = $maintenanceReqManager;
    }

    public function index(Request $request)
    {
        $data = new MaintenanceReq();
        /**DCEN can also add request.
         *DCEN will get all department in department list. CR:16-03-2022**/
        if(Auth::user()->employee->department && !Auth::user()->hasPermission('CAN_DEPUTY_CHIEF_ENG_MDA')){
            $departments=Department::where('status', '=','A')
                ->where('id',Auth::user()->employee->department->department_id)
                ->orderBy('name', 'ASC')
                ->get();
        }else{
            $departments=Department::where('status', '=','A')
/*                ->where('department_id')*/
                ->orderBy('name', 'ASC')
                ->get();
        }

        return view('mwe.maintenance_req',
            ['departments'=>$departments,
                'data'=>$data,
            ]);
    }

    public function store(Request $request)
    {//dd($request);
        if ($request->isMethod("POST")) {
            $request->validate([
                'department_id' => 'required',
                'vessel_id' => 'required',
                //'incharge_emp_id' => 'required',
                'description' => 'required'
            ]);
            $managerRes = $this->maintenanceReqManager->maintenanceCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function edit($id)
    {
        /**All departments were serving in edit mode. Identified the case as bug and added validation. CR:16-03-2022**/
        //$departments=Department::where('status', '=','A')->orderBy('name', 'ASC')->get();

        if(Auth::user()->employee->department && !Auth::user()->hasPermission('CAN_DEPUTY_CHIEF_ENG_MDA')){
            $departments=Department::where('status', '=','A')
                ->where('id',Auth::user()->employee->department->department_id)
                ->orderBy('name', 'ASC')
                ->get();
        }else{
            $departments=Department::where('status', '=','A')
                ->orderBy('name', 'ASC')
                ->get();
        }

        $vessels=$this->maintenanceReqManager->showVesselMaster(isset(MaintenanceReq::findOrFail($id)->vessel_id)?MaintenanceReq::findOrFail($id)->vessel_id:'');
        $req=MaintenanceReq::where('id','=',$id)->with('attachment')->get()[0];
        return view('mwe.maintenance_req', [
            'data' => MaintenanceReq::where('id','=',$id)->with('attachment')->get()[0],
            'departments'=>$departments,
            'vessels'=>$vessels,
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'department_id' => 'required',
                'vessel_id' => 'required',
                'vessel_master_id' => 'required',
                'description' => 'required'
            ]);
            $managerRes = $this->maintenanceReqManager->maintenanceCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->maintenanceReqManager->maintenanceCud('D', $request, $id);
            $res = [
                'success'=>($managerRes['status'])?true:false,
                'message' => $managerRes['status_message']
            ];
        }else{
            $res = [
                'success'=>false,
                'message' => 'Invalid required data.'
            ];
        }
        return $res;
    }

    public function datatable($id)
    {
        $dataTable = $this->maintenanceReqManager->maintenanceDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->editColumn('department', function ($data) {
                return !empty($data['department'])?$data['department']['name']:'';
            })
            ->addColumn('created_at', function ($data) {
                return Carbon::parse($data->created_at)->format('d-m-Y h:i A');
            })
            ->editColumn('vessel', function ($data) {
                return !empty($data['vessel'])?$data['vessel']['name']:'';
            })
            ->editColumn('vessel_master', function ($data) {
                return !empty($data['vesselMaster'])?$data['vesselMaster']['emp_name']:'';
            })
            ->editColumn('inspector_assigned_by_emp_id', function ($data) {
                return !empty($data['assignedBy'])?$data['assignedBy']['emp_name']:'N/A';
            })
            ->editColumn('current_status', function ($data) {
                return HelperClass::getReqStatus($data['status']);
            })
            ->editColumn('print', function ($data) {
                if($data['status']==='12'){
                    $optionHtml =  '<form name="report-generator" id="report-generator" method="post" target="_blank" action="'.route('report', ['title' => 'maintenance-request-details']).'">
                            '.csrf_field().'
                            <input type="hidden" name="xdo" value="/~weblogic/MWE/RPT_MAINTENANCE_REQUEST_DETAILS.xdo"/>
                            <input type="hidden" name="type" id="type" value="pdf" />
                            <input type="hidden" name="p_maintenance_req_id" id="p_maintenance_req_id" value="'.$data['id'].'" />
                            <input type="hidden" name="p_workshop_id" id="p_workshop_id" />
                            <button type="submit" class="btn btn-sm cursor-pointer" data-toggle="tooltip" data-placement="top" title="Click to print report"><i class="bx bx-printer"></i></button>
                        </form>';
                    return $optionHtml;
                }
            })
            ->addColumn('action', function ($data) use($id) {
                $optionHtml='';
                if (Auth::user()->hasPermission('CAN_DOC_MASTER_MDA') && in_array(HelperClass::getReqCurrentStatus($data['id']),ConfigRole::can_be_edit_maintenance_req)){
                    $optionHtml =  '<a href="' . route('mwe.operation.maintenance-request-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                }
                if(Auth::user()->hasPermission('CAN_DEPUTY_CHIEF_ENG_MDA')&& in_array(HelperClass::getReqCurrentStatus($data['id']),ConfigRole::can_be_auth_maintenance_req)){
                    /**DCEN can edit only his own request. CR:16-03-2022**/
                    /*if ($data->requester_emp_id == Auth::user()->emp_id){
                            $optionHtml .=  '<a href="' . route('mwe.operation.maintenance-request-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                    }*/
                    if ($data->requester_emp_id == Auth::user()->emp_id){
                        if($data->status!='12'){
                            $optionHtml .=  '<a href="' . route('mwe.operation.maintenance-request-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                        }
                    }

                    if($id != $data['id'] ){
                        if($data['status']==='12'){
                            //$optionHtml .= ' <a class="confirm-alert text-success" href="' . route('mwe.operation.maintenance-req-auth-by-den', $data['id']) . '" data-toggle="tooltip" data-placement="top" title="Click to show certificate"><i class="bx bxs-certification cursor-pointer"></i></a>';
                            $optionHtml =  '<form name="report-generator" id="report-generator" method="post" target="_blank" action="'.route('report', ['title' => 'maintenance-request-details']).'">
                            '.csrf_field().'
                            <input type="hidden" name="xdo" value="/~weblogic/MWE/RPT_MAINTENANCE_REQUEST_DETAILS.xdo"/>
                            <input type="hidden" name="type" id="type" value="pdf" />
                            <input type="hidden" name="p_maintenance_req_id" id="p_maintenance_req_id" value="'.$data['id'].'" />
                            <input type="hidden" name="p_workshop_id" id="p_workshop_id" />
                            <button type="submit" class="btn btn-sm cursor-pointer confirm-alert text-success" data-toggle="tooltip" data-placement="top" title="Click to show certificate"><i class="bx bxs-certification cursor-pointer"></i></button>
                        </form>';
                            return $optionHtml;
                        }else{
                            $optionHtml .= ' <a class="confirm-alert text-danger" href="' . route('mwe.operation.maintenance-req-auth-by-den', $data['id']) . '" data-toggle="tooltip" data-placement="top" title="Click for authorized"><i class="bx bx-check-double cursor-pointer"></i></a>';
                        }
                    }
                }
                if(Auth::user()->hasPermission('CAN_XEN_MDA')&& in_array(HelperClass::getReqCurrentStatus($data['id']),ConfigRole::can_be_auth_maintenance_req)){
                    if($id != $data['id'] ){
                        if($data['status']==='12'){
                            $optionHtml .= ' <a class="confirm-alert text-success" href="' . route('mwe.operation.maintenance-req-auth-by-xen', $data['id']) . '" data-toggle="tooltip" data-placement="top" title="Click to show certificate"><i class="bx bxs-certification cursor-pointer"></i></a>';
                        }else{
                            $optionHtml .= ' <a class="confirm-alert text-danger" href="' . route('mwe.operation.maintenance-req-auth-by-xen', $data['id']) . '" data-toggle="tooltip" data-placement="top" title="Click for authorized"><i class="bx bx-check cursor-pointer"></i></a>';
                        }

                    }
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                if(isset($data['status']) && !empty($data['status'])){
                    $status = HelperClass::getReqStatus($data['status']);
                    $res = '<span  class="badge badge-pill" style="background-color:'.$status->color_code.'">';
                    $res .= $status->name;
                    $res .='</span>';
                    return $res;
                }else{
                    $res = '';
                }
                return $res;
            })
            ->escapeColumns([])
            ->make(true);
    }
    public function authorizedByXEN($id)
    {
//        dd(MaintenanceReq::findOrFail($id)->deputy_chief_eng_user_id);
        $departments=Department::all()->sortBy('name');
        $vessels=$this->maintenanceReqManager->showVesselMaster(isset(MaintenanceReq::findOrFail($id)->vessel_id)?MaintenanceReq::findOrFail($id)->vessel_id:'');
        $vessel_master=$this->employeeManager->getEmployee(isset(MaintenanceReq::findOrFail($id)->vessel_master_id)?MaintenanceReq::findOrFail($id)->vessel_master_id:'');
        $assigned_inspector=$this->employeeManager->getMaintenanceSSAEN(isset(MaintenanceReq::findOrFail($id)->inspector_emp_id)?MaintenanceReq::findOrFail($id)->inspector_emp_id:'');
        $deputyEngineer=$this->employeeManager->getMaintenanceDeputyEngineer(MaintenanceReq::findOrFail($id)->deputy_chief_eng_user_id);
        return view('mwe.maintenance_req_auth', [
            'data' => MaintenanceReq::findOrFail($id),
            'departments'=>$departments,
            'vessels'=>$vessels,
            'vesselMaster'=>$vessel_master,
            'assigned_inspector'=>$assigned_inspector,
            'deputy_engineer'=>$deputyEngineer
        ]);
    }

    public function authorizedByDEN($id)
    {
        $departments=Department::all()->sortBy('name');
        $vessels=$this->maintenanceReqManager->showVesselMaster(isset(MaintenanceReq::findOrFail($id)->vessel_id)?MaintenanceReq::findOrFail($id)->vessel_id:'');
        $vessel_master=$this->employeeManager->getEmployee(isset(MaintenanceReq::findOrFail($id)->vessel_master_id)?MaintenanceReq::findOrFail($id)->vessel_master_id:'');
        $assigned_inspector=$this->employeeManager->getMaintenanceSAEN(isset(MaintenanceReq::findOrFail($id)->inspector_emp_id)?MaintenanceReq::findOrFail($id)->inspector_emp_id:'');
        return view('mwe.maintenance_req_auth_dcen', [
            'data' => MaintenanceReq::findOrFail($id),
            'departments'=>$departments,
            'vessels'=>$vessels,
            'vesselMaster'=>$vessel_master,
            'assigned_inspector'=>$assigned_inspector
        ]);
    }

    public function storeAuthorizedByXEN(Request $request,$id){

        if ($request->isMethod("POST")) {
            $request_current_status=HelperClass::getReqCurrentStatus($id);
            if($request_current_status==2){
                $request->validate([
                    'status' => 'required',
                    'inspector_emp_id' => 'required'
                ]);
            }
            $managerRes = $this->maintenanceReqManager->xenAuthorization('',$request,$id);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function storeAuthorizedByDEN(Request $request,$id){

        if ($request->isMethod("POST")) {
            $request_current_status=HelperClass::getReqCurrentStatus($id);
            if($request_current_status==2){
                $request->validate([
                    'status' => 'required',
                ]);
            }
            $managerRes = $this->maintenanceReqManager->deputyChiefEngAuthorization('',$request,$id);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

}
