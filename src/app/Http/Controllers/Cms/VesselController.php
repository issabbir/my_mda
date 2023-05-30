<?php

namespace App\Http\Controllers\Cms;
use App\Contracts\Cms\CommonContract;
use App\Contracts\Cms\VesselContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Entities\Admin\LDepartment;
use App\Entities\Cms\CpaVessel;
use App\Entities\Cms\CpaVesselEngine;
use App\Entities\Cms\EmployeeDuties;
use App\Entities\Cms\FuelConsumptionDtl;
use App\Entities\Cms\FuelConsumptionMst;
use App\Entities\Cms\LFuelType;
use App\Entities\Cms\LVesselEngineType;
use App\Entities\Cms\LVesselType;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Pmis\Employee\EmployeeManager;
use Carbon\Traits\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class VesselController extends Controller
{

    public $employeeManager;
    public $vesselManager;
    public  $commonManager;

    public function __construct(CommonContract $commonManager,EmployeeContract $employeeManager,VesselContract $vesselManager)
    {
        $this->commonManager=$commonManager;
        $this->employeeManager = $employeeManager;
        $this->vesselManager = $vesselManager;
    }

    /************Start vessel *************/
    public function vesselIndex(Request $request)
    {
        $data = new CpaVessel();
        $vessel_type=LVesselType::all()->sortBy('name');
        $fuel_type=LFuelType::all()->sortBy('fuel_type_name');
        $depts=LDepartment::all()->sortBy('department_name');
        return view('cms.vessel.index',
            ['data'=>$data,
              'vessel_type'=>$vessel_type,
              'fuel_type'=>$fuel_type,
              'depts'=>$depts
            ]);
    }

    public function vesselStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'name' => 'required',
                'vessel_type_id' => 'required',
                'fuel_type_id' => 'required',
                'incharge_emp_id' => 'required',
                'department_id' => 'required',
            ]);
            $managerRes = $this->vesselManager->vesselStore('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }



    public function vesselEdit($id)
    {
        $data =CpaVessel::where('id',$id)->first();
        $vessel_type=LVesselType::all()->sortBy('name');
        $fuel_type=LFuelType::all()->sortBy('fuel_type_name');
        $depts=LDepartment::all()->sortBy('department_name');
        $incharge=$this->employeeManager->getEmployee(isset(CpaVessel::findOrFail($id)->incharge_emp_id)?CpaVessel::findOrFail($id)->incharge_emp_id:'');
        return view('cms.vessel.index',
            ['data'=>$data,
             'vessel_type'=>$vessel_type,
             'fuel_type'=>$fuel_type,
             'incharge'=>$incharge,
             'depts'=>$depts
            ]);
    }

    public function vesselUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'name' => 'required',
                'vessel_type_id' => 'required',
                'fuel_type_id' => 'required',
                'incharge_emp_id' => 'required',
                'department_id' => 'required',
            ]);
            $managerRes = $this->vesselManager->vesselStore('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function vesselDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->vesselManager->vesselStore('D', $request, $id);
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

    public function vesselDatatable()
    {
        $dataTable = $this->vesselManager->vesselData();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->editColumn('vessel_type', function ($data) {
                return !empty($data['vessel_type'])?$data['vessel_type']['name']:'';
            })
            ->editColumn('fuel_type', function ($data) {
                return !empty($data['fuel_type'])?$data['fuel_type']['fuel_type_name']:'';
            })
            ->editColumn('incharge', function ($data) {
                return !empty($data['employee'])?$data['employee']['emp_name']:'';
            })
            ->addColumn('action', function ($data) {
                $optionHtml =  '<a href="' . route('cms.vessel.edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                $optionHtml .= ' <a class="" data-toggle="tooltip" data-placement="top" title="Click to mapping vessel engine" href="' . route('cms.vessel-engine-mapping',  ['vessel_id'=>$data['id']]) . '"><i class="bx bx-wrench cursor-pointer"></i></a>';
//                $optionHtml .= ' <a class="" data-toggle="tooltip" data-placement="top" title="Click to create fuel consumption" href="' . route('cms.fuel-consumption',  ['vessel_id'=>$data['id']]) . '"><i class="bx bx-gas-pump cursor-pointer"></i></a>';
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return  HelperClass::getStatusName($data['status']);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /************Start fuel consumption*************/

    public function fuelConsumptionIndex(Request $request)
    {
        $data=new FuelConsumptionMst();
        $vessel_info =CpaVessel::where('id',$request->get('vessel_id'))->first();
        $vessel_engine=CpaVesselEngine::where('cpa_vessel_id',$request->get('vessel_id'))
            ->orderBy('engine_type_id','asc')
            ->get();
        return view('cms.vessel.fuel_consumption_index',
            ['data' => $data,
             'vessel_engine'=>$vessel_engine,
             'vessel_info'=>$vessel_info,
             'prv_consumption_data'=>$this->commonManager->getLastConsumption($request->get('vessel_id'))
            ]);
    }

    public function fuelConsumptionStore(Request $request)
    {

        if ($request->isMethod("POST")) {
            $request->validate([
                'consumption_from' => 'required',
                'consumption_to' => 'required',
                'consumption_ref_no' => 'required',
            ]);
            $managerRes = $this->vesselManager->fuelConsumptionMstStore('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function fuelConsumptionStoreSendToApproval(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'consumption_from' => 'required',
                'consumption_to' => 'required',
                'consumption_ref_no' => 'required',
            ]);
            $managerRes = $this->vesselManager->fuelConsumptionStoreWithApproval('I', $request);
            return $managerRes;
        }
    }

    public function fuelConsumptionEdit($id)
    {
        $data=FuelConsumptionMst::where('fuel_consumption_mst_id',$id)->first();
        $vessel_info =CpaVessel::where('id',$data->cpa_vessel_id)->first();
        $fuel_items=FuelConsumptionDtl::where('fuel_consumption_mst_id',$id)->get();
        return view('cms.vessel.fuel_consumption_index',
            [ 'data' => $data,
              'fuel_items'=>$fuel_items,
              'vessel_info'=>$vessel_info,
              'prv_consumption_data'=>$this->commonManager->getLastConsumption($data->cpa_vessel_id)
            ]);
    }

    public function fuelConsumptionUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'consumption_from' => 'required',
                'consumption_to' => 'required',
            ]);
            $managerRes = $this->vesselManager->fuelConsumptionMstStore('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function fuelConsumptionDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->vesselManager->fuelConsumptionMstStore('D', $request, $id);
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

    public function showFuelConsumptionApproval(Request $request){

        $data=FuelConsumptionMst::where('fuel_consumption_mst_id',$request->get('fuel_consumption_mst_id'))->first();
        $vessel_info =CpaVessel::where('id',$data->cpa_vessel_id)->first();
        $fuel_items=FuelConsumptionDtl::where('fuel_consumption_mst_id',$request->get('fuel_consumption_mst_id'))->get();
        return view('cms.vessel.fuel_consumption_view',
            [   'data' => $data,
                'approval-data'=>$this->commonManager->showAuthorizedData($request),
                'mapping_data'=>$this->commonManager->showMappingData($request->get('fuel_consumption_mst_id')),
                'fuel_items'=>$fuel_items,
                'vessel_info'=>$vessel_info,
                'prv_consumption_data'=>$this->commonManager->getLastConsumption($data->cpa_vessel_id)
            ]);

    }

    public function storeFuelConsumptionApproval(Request $request){

        if ($request->isMethod("POST")) {
            $managerRes = $this->vesselManager->consumptionApprovalStore($request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }

    }

    public function fuelConsumptionDatatable(Request  $request)
    {
        $dataTable = $this->vesselManager->fuelConsumptionData($request);
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->editColumn('formatted_consumption_from_date', function ($data) {
                return HelperClass::defaultDateTimeFormat($data['consumption_from'],'LOCALDATE') ;
            })
            ->editColumn('formatted_consumption_to_date', function ($data) {
                return HelperClass::defaultDateTimeFormat($data['consumption_to'],'LOCALDATE') ;
            })
            ->editColumn('formatted_received_date', function ($data) {
                return HelperClass::defaultDateTimeFormat($data['received_date'],'LOCALDATE') ;
            })
            ->addColumn('action', function ($data) {
                $optionHtml='';
                if(!in_array($data['status'],['A','P','R'])){
                    $optionHtml =  '<a href="' . route('cms.fuel-consumption.edit', $data['fuel_consumption_mst_id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>||';
                }
                $optionHtml .=  '<a data-toggle="tooltip" data-placement="top" title="Click to show approval stage" href="' . route('cms.fuel-consumption.show-approval-stage',['fuel_consumption_mst_id'=>$data['fuel_consumption_mst_id']]) . '" class=""><i class="bx bx-show cursor-pointer"></i></a>';
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return  HelperClass::getFuelConsumptionStatus($data['status']);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /************Start vessel engine mapping *************/

    public function vesselEngineMappingIndex(Request $request)
    {
        $data=new CpaVesselEngine();
        $vessel_info =CpaVessel::where('id',$request->get('vessel_id'))->first();
        $engine_type=LVesselEngineType::where('status','A')
            ->orderBy('engine_name','asc')
            ->get();
        $fuel_type=LFuelType::where('status','A')
            ->orderBy('fuel_type_name','asc')
            ->get();
        return view('cms.vessel.vessel_engine_mapping',
            ['data' => $data,
             'engine_type'=>$engine_type,
             'fuel_type'=>$fuel_type,
              'vessel_info'=>$vessel_info
            ]);
    }

    public function vesselEngineMappingStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'engine_type_id' => 'required',
                'hourly_consumed_fuel' => 'required',
            ]);
            $managerRes = $this->vesselManager->vesselEngineMappingStore('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function vesselEngineMappingEdit($id)
    {
        $data=CpaVesselEngine::where('vessel_engine_id',$id)->first();
        $vessel_info =CpaVessel::where('id',$data->cpa_vessel_id)->first();
        $engine_type=LVesselEngineType::where('status','A')
            ->orderBy('engine_name','asc')
            ->get();
        $fuel_type=LFuelType::where('status','A')
            ->orderBy('fuel_type_name','asc')
            ->get();
        return view('cms.vessel.vessel_engine_mapping',
            ['data' => $data,
             'engine_type'=>$engine_type,
             'fuel_type'=>$fuel_type,
             'vessel_info'=>$vessel_info
            ]);
    }

    public function vesselEngineMappingUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'engine_type_id' => 'required',
                'hourly_consumed_fuel' => 'required',
            ]);
            $managerRes = $this->vesselManager->vesselEngineMappingStore('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function vesselEngineMappingDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->vesselManager->vesselEngineMappingStore('D', $request, $id);
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

    public function vesselEngineMappingDatatable(Request  $request)
    {
        $dataTable = $this->vesselManager->vesselEngineMappingData($request);
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->editColumn('engine_type', function ($data) {
                return !empty($data['engine'])?$data['engine']['engine_name']:'';
            })
            ->editColumn('fuel_type', function ($data) {
                return !empty($data['fuel_type'])?$data['fuel_type']['fuel_type_name']:'';
            })
            ->addColumn('action', function ($data) {
                $optionHtml =  '<a href="' . route('cms.vessel-engine-mapping.edit', $data['vessel_engine_id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return  HelperClass::getStatusName($data['status']);
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function showUserWiseVessel(Request $request){

        if ($request->get('ref_module')){
            Session::put('ref_module_key', $request->get('ref_module'));
        }
        $data = new CpaVessel();
        $vessel_type=LVesselType::all()->sortBy('name');
        $fuel_type=LFuelType::all()->sortBy('fuel_type_name');
        return view('cms.vessel.dept_wise_index',
            ['data'=>$data,
                'vessel_type'=>$vessel_type,
                'fuel_type'=>$fuel_type
            ]);
    }

    public function userWiseVesselDatatable()
    {
        $dataTable = $this->vesselManager->vesselData();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->editColumn('vessel_type', function ($data) {
                return !empty($data['vessel_type'])?$data['vessel_type']['name']:'';
            })
            ->editColumn('fuel_type', function ($data) {
                return !empty($data['fuel_type'])?$data['fuel_type']['fuel_type_name']:'';
            })
            ->editColumn('incharge', function ($data) {
                return !empty($data['employee'])?$data['employee']['emp_name']:'';
            })
            ->addColumn('action', function ($data) {
                $optionHtml = ' <a class="" data-toggle="tooltip" data-placement="top" title="Click to create fuel consumption" href="' . route('cms.fuel-consumption',  ['vessel_id'=>$data['id']]) . '"><i class="bx bx-gas-pump cursor-pointer"></i></a>';
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return  HelperClass::getStatusName($data['status']);
            })
            ->escapeColumns([])
            ->make(true);
    }

}
