<?php

namespace App\Http\Controllers\Mda;

use App\Contracts\Mda\MooringChargeContract;
use App\Contracts\Mda\SettingsContract;
use App\Contracts\Mda\cashCollectionContract;
use App\Entities\Mda\CollectionSlip;
use App\Entities\Mda\CpaVessel;
use App\Entities\Mda\LocalVessel;
use App\Entities\Mda\LCollectionSlipType;
use App\Entities\Mda\MooringCharge;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MooringChargeController extends Controller
{
    public $mooringChargeManager;
    public function __construct(MooringChargeContract $mooringChargeManager)
    {
        $this->mooringChargeManager = $mooringChargeManager;
    }

    /************Start slip generation*************/
    public function create(Request $request)
    {
        $data = new CollectionSlip();
        $localVesselNames= LocalVessel::where("status", "=", "A")->orderBy("name","ASC")->get();
        $slip_types=LCollectionSlipType::where("status", "=", "A")->orderBy("name","ASC")->get();
        return view("mda.mooring_charge",
            [
                "data"=>$data,"localVesselNames"=>$localVesselNames,"slip_types"=>$slip_types,
            ]);
    }

    public function store(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'form_no' => 'required',
                'collection_date' => 'required',
                'slip_type_id' => 'required',
                'local_vessel_id' => 'required',
                'period_from' => 'required',
                'period_to' => 'required|after_or_equal:period_from',
                'mooring_charge_amnt' => 'required',
                'vat_amount' => 'required',
            ],
                [
'local_vessel_id.required'=>'The local vessel field is required. '
                ]);
            $managerRes = $this->mooringChargeManager->mooringChargeCud('I', $request);
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
        $localVesselNames= LocalVessel::where("status", "=", "A")->orderBy("name","ASC")->get();
        $slip_types=LCollectionSlipType::where("status", "=", "A")->orderBy("name","ASC")->get();
        return view("mda.mooring_charge",
            [
                'data' => MooringCharge::findOrFail($id),"localVesselNames"=>$localVesselNames,"slip_types"=>$slip_types,

            ]);
//        return view('mda.cc_slip_generation', [
//            'data' => CollectionSlip::findOrFail($id)
//        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
//                'name' => 'required',
//                'status' => 'required',
            ]);
            $managerRes = $this->mooringChargeManager->mooringChargeCud('U', $request, $id);

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
            $managerRes = $this->mooringChargeManager->mooringChargeCud('D', $request, $id);
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
        $dataTable = $this->mooringChargeManager->mooringChargeDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                if($data['status'] !='A'){
                    $optionHtml =  '<a href="' . route('mooring-charge-edit', $data['mooring_charge_id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                    if($id != $data['id'] ){
                        $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('mooring-charge-edit', $data['mooring_charge_id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                    }
                }else{
                    $optionHtml = '';
                }

                return $optionHtml;
            })
            ->editColumn('period_form', function ($data) {
                return HelperClass::defaultDateTimeFormat($data['period_from'], 'date');
            })
            ->editColumn('period_to', function ($data) {
                return  HelperClass::defaultDateTimeFormat($data['period_to'], 'date');
            })
            ->editColumn('total', function ($data) {
                return   $data['mooring_charge_amnt'] +  $data['vat_amount'] ;
            })
            ->editColumn('status', function ($data) {
                if ($data['status'] == 'A'){
                    return "Collected";
                }else{
                    return "Prepared";
                }
            })
            ->make(true);
    }

    /************End collection slip types*************/

}
