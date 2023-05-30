<?php

namespace App\Http\Controllers\Cms;
use App\Contracts\Cms\ApprovalContract;
use App\Contracts\Cms\CommonContract;
use App\Entities\Cms\CpaVessel;
use App\Entities\Cms\FuelConsumptionDtl;
use App\Entities\Cms\FuelConsumptionMst;
use App\Http\Controllers\Controller;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ApprovalController extends Controller
{
    use HasPermission;
    public  $commonManager;
    public  $approvalManager;

    public function __construct(CommonContract $commonManager,ApprovalContract $approvalManager)
    {
        $this->commonManager=$commonManager;
        $this->approvalManager=$approvalManager;
    }

    public function index(Request $request)
    {
        return view('cms.approval.list',
            ['workflow'=>$this->commonManager->getAllWorkFlowMaster(),
            ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'auth_status' => 'required',
        ]);
        $managerRes = $this->approvalManager->authorized($request);
        if ($managerRes['status']==true) {
            $message = redirect(route('approval.list'))->with('success', $managerRes['status_message']);
        } else {
            $message = redirect()->back()->with('error', $managerRes['status_message']);
        }
        return $message;
    }

    public function show(Request $request){
        $validator = Validator::make($request->all(), [
            'ref_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return redirect(route('approval.list'))
                ->withErrors($validator)
                ->withInput();
        }else{
            $data=FuelConsumptionMst::where('fuel_consumption_mst_id',$request->get('ref_id'))->first();
            $vessel_info =CpaVessel::where('id',$data->cpa_vessel_id)->first();
            $fuel_items=FuelConsumptionDtl::where('fuel_consumption_mst_id',$request->get('ref_id'))->get();
            return view('cms.approval.index',
                [ 'data'=>$this->commonManager->showAuthorizedData($request),
                  'mapping_data'=>$this->commonManager->showMappingData($request->get('ref_id')),
                  'prv_consumption_data'=>$this->commonManager->getLastConsumption($data->cpa_vessel_id),
                  'fuel_items'=>$fuel_items,
                  'vessel_info'=>$vessel_info
                ]);
        }
    }

    public function datatable(Request $request)
    {
        $dataTable = $this->commonManager->showWorkflowData($request);
//        dd($dataTable);
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('vessel_incharge', function ($data) {
                return isset($data['consumption'])?isset($data['consumption']['vessel']['employee'])?$data['consumption']['vessel']['employee']['emp_name']:'':'';
            })
            ->addColumn('vessel_name', function ($data) {
                return isset($data['consumption'])?$data['consumption']['vessel']['name']:'';
            })
            ->addColumn('authorization_for', function ($data) {
                if(isset($data['workflow_master_id']) && !empty($data['workflow_master_id'])){
                    $res ='<span  class="badge badge-info">';
                    $res .= isset($data['workflow_master'])?$data['workflow_master']['workflow_name']:'';
                    $res .='</span>';
                }else{
                    $res = '';
                }
                return $res;
            })
            ->addColumn('cur_status', function ($data) {
                if(isset($data['seq']) && !empty($data['seq'])){
                    $res ='<span  class="badge badge-light-danger">';
                    $res .= isset($data['workflow_detail'])?$data['workflow_detail']['step_name']:'';
                    $res .='</span>';
                }else{
                    $res = '';
                }
                return $res;
            })
            ->addColumn('action', function ($data) {
                $optionHtml =  '<a href="' . route('approval.show',['ref_id'=>$data->reference_id,'id'=>$data->workflow_mapping_id,'ref_ob'=>$data->reference_table]) . '" class="" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Click to authorized this"><i class="bx bx-show cursor-pointer"></i></a>';
                return $optionHtml;
            })
            ->escapeColumns([])
            ->make(true);
    }

}
