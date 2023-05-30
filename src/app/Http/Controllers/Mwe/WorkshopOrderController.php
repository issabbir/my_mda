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
use App\Managers\Mwe\WorkshopManager;
use App\Managers\Pmis\Employee\EmployeeManager;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WorkshopOrderController extends Controller
{
    public $employeeManager;
    public $inspectionReqManager;
    public $workshopManager;

    public function __construct(EmployeeContract $employeeManager,InspectionReqManager $inspectionReqManager,WorkshopManager $workshopManager)
    {
        $this->employeeManager = $employeeManager;
        $this->inspectionReqManager = $inspectionReqManager;
        $this->workshopManager=$workshopManager;
    }

    public function index()
    {
        return view('mwe.workshop_order_list');
    }

    public function view($id)
    {

        $maintenance_req_data=MaintenanceReq::where('id',$id)->with('vessel','department','vesselMaster','assignedInspector','slipwayName')->first();
        $workshops=Workshop::where('status', '=','A')->orderBy('name', 'ASC')->get();
        $assigned_sae_mechanical=(!$maintenance_req_data->sae_mechanical_emp_id)?'':$this->employeeManager->getEmployee($maintenance_req_data->sae_mechanical_emp_id);
        return view('mwe.workshop_order_view',
            ['maintenanceReqData' => $maintenance_req_data,
              'workshops'=>$workshops,
              'assigned_sae_mechanical'=>$assigned_sae_mechanical,
              'data'=>$this->inspectionReqManager->getVesselInspection($id),
            ]);
    }


    public function store(Request $request,$id)
    {
        if ($request->isMethod("POST")) {
            $managerRes = $this->inspectionReqManager->workshopAssign($request,$id);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function deleteInspectionJob(Request $request)
    {
        $managerRes = $this->inspectionReqManager->maintenanceInspectionJob('D',$request,$request->get('inspection_id'));
        $workshops=Workshop::where('status', '=','A')->orderBy('name', 'ASC')->get();
        $view = view('mwe.partial.inspection_jobs', [
            'maintenanceReqData' => MaintenanceReq::where('id',$request->get("maintenance_req_id"))->with('vessel','department','vesselMaster','assignedInspector')->first(),
            'data'=>$this->inspectionReqManager->getVesselInspection($request->get("maintenance_req_id")),
            'workshops'=>$workshops
        ])->render();
        return response()->json(['html' => $view,'response'=>$managerRes]);

    }

    public function datatable($id)
    {
        $dataTable = $this->workshopManager->workshopOrderDataTable();
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
                        $optionHtml = ' <a class="btn btn-primary btn-sm" href="' . route('mwe.operation.workshop-order-view', $data['id']) . '">Workshop Order</a>';
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
