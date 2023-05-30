<?php

namespace App\Http\Controllers\Mwe;

use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Entities\Mwe\MaintenanceReq;
use App\Entities\Mwe\Product;
use App\Entities\Mwe\Unit;
use App\Entities\Mwe\Workshop;
use App\Enums\Mwe\ConfigRole;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Mwe\InspectionReqManager;
use App\Managers\Mwe\WorkshopManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class WorkshopRequisitionController extends Controller
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

    public function index()
    {
        return view('mwe.workshop_requisition_list');
    }

    public function makeRequisition($id,$workshopId)
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
        }//dd($this->workshopManager->getMaintenanceReqDataByWorkshop($id, $workshopId));

        return view('mwe.workshop_requisition',
            ['maintenanceReqData' => MaintenanceReq::where('id', $id)->with('vessel', 'department', 'vesselMaster', 'assignedInspector')->first(),
                'workshop' => Workshop::where('id', $workshopId)->first(),
                'task' => $this->workshopManager->getMaintenanceReqDataByWorkshop($id, $workshopId),
                'units' => Unit::all()->sortBy('asc'),
                'products' => Product::orderBy('name', 'asc')->get(),
                'send_to' => $users,
            ]);
    }

    public function addRequisitionItem(Request $request)
    {
        if ($request->isMethod("POST")) {
            $managerRes = $this->workshopManager->addWorkshopRequisitionItem('I',$request);
            $view = view('mwe.partial.workshop_req_items', [
                'data'=>$this->workshopManager->getWorkshopRequisitionItemByInspectionJob($request->get('vessel_inspection_id')),
                'task_data'=>$this->workshopManager->getVesselInspectionData($request->get('vessel_inspection_id')),
            ])->render();
            return response()->json(['html' => $view,'response'=>$managerRes]);
        }
    }

    public function showWorkshopTaskDetails(Request $request)
    {
        if ($request->isMethod("POST")) {
            $view = view('mwe.partial.workshop_req_items', [
                'data'=>$this->workshopManager->getWorkshopRequisitionItemByInspectionJob($request->get('vessel_inspection_id')),
            ])->render();
            return response()->json(['html' => $view]);
        }
    }

    public function removeWorkshopReqItem(Request $request)
    {
        $managerRes = $this->workshopManager->addWorkshopRequisitionItem('D',$request,$request->get('workshop_req_item_id'));
        $view = view('mwe.partial.workshop_req_items', [
            'data'=>$this->workshopManager->getWorkshopRequisitionItemByInspectionJob($request->get('vessel_inspection_id')),
        ])->render();
        return response()->json(['html' => $view,'response'=>$managerRes]);

    }

    public function processWorkshopRequisition(Request $request)
    {//dd($request->all());
        if ($request->isMethod("POST")) {
            $request->validate([
                'job_number' => 'required',
                //'forward_to' => 'required',
                'task_details' => 'required'
            ]);
            $managerRes = $this->workshopManager->storeWorkshopRequisition($request);
            $view = view('mwe.partial.workshop_req_items', [
                'data'=>$this->workshopManager->getWorkshopRequisitionItemByInspectionJob($request->get('vessel_inspection_id'))
            ])->render();
            return response()->json(['html' => $view,'response'=>$managerRes]);
        }
    }

    public function completeWorkshopRequisition(Request $request)
    {
        if ($request->isMethod("POST")) {
            $managerRes = $this->workshopManager->workshopRequisitionCompete($request);
            $view = view('mwe.partial.workshop_req_items', [
                'data'=>$this->workshopManager->getWorkshopRequisitionItemByInspectionJob($request->get('vessel_inspection_id'))
            ])->render();
            return response()->json(['html' => $view,'response'=>$managerRes]);
        }
    }

    public function datatable()
    {//DEPARTMENT_NAME, NAME, VESSEL_MASTER, INSPECTOR_ASSIGNED_BY_EMP_NAME
        $dataTable = $this->workshopManager->RequisitionDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->editColumn('department', function ($data) {
                return !empty($data->department_name)?$data->department_name:'';
            })
            ->editColumn('vessel', function ($data) {
                return !empty($data->name)?$data->name:'';
            })
            ->editColumn('vessel_master', function ($data) {
                return !empty($data->vessel_master)?$data->vessel_master:'';
            })
            ->editColumn('assigned_inspector', function ($data) {
                return !empty($data->assigned_insp_name)?$data->assigned_insp_name:'';
            })
            ->editColumn('inspector_assigned_by_emp_id', function ($data) {
                return !empty($data->inspector_assigned_by_emp_name)?$data->inspector_assigned_by_emp_name:'';
            })
            ->addColumn('created_at', function ($data) {
                return Carbon::parse($data->created_at)->format('d-m-Y h:i A');
            })
            ->addColumn('action', function ($data)  {
                if(in_array(HelperClass::getReqCurrentStatus($data->id),ConfigRole::can_be_make_requisition)){
                    $workshopAction=$this->workshopManager->getWorkshopRequisition($data->id);
                    $actions='<a  href=""></a>';
                    foreach ($workshopAction as $action){
                        $actions.= '<a class="dropdown-item"  href=" '.route("mwe.operation.workshop-requisition-create",[$data->id,$action->workshop_id]).'">'.$action->workshop->name.'</a>';
                    }
                    return '<div class="dropdown">
                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="requisitionLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         Make Requisition For
                    </a><div class="dropdown-menu" aria-labelledby="requisitionLink">'.$actions.'</div></div>';
                }
            })
            ->editColumn('status', function ($data) {
                if(isset($data->status) && !empty($data->status)){
                    $status = HelperClass::getReqStatus($data->status);
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

}
