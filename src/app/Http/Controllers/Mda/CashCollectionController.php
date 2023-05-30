<?php

namespace App\Http\Controllers\Mda;

use App\Contracts\Mda\SettingsContract;
use App\Contracts\Mda\cashCollectionContract;
use App\Entities\Mda\CollectionSlip;
use App\Entities\Mda\CpaVessel;
use App\Entities\Mda\LLicenseOffice;
use App\Entities\Mda\LocalVessel;
use App\Entities\Mda\LCollectionSlipType;
use App\Entities\Mwe\EmpOfficeSetup;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CashCollectionController extends Controller
{
    public $cashCollectionManager;
    public function __construct(cashCollectionContract $cashCollectionManager)
    {
        $this->cashCollectionManager = $cashCollectionManager;
    }

    /************Start slip generation*************/
    public function slipGenerationCreate(Request $request)
    {
        $data = new CollectionSlip();
        $localVesselNames= LocalVessel::where("status", "=", "A")->orderBy("name","ASC")->get();
        $slip_types=LCollectionSlipType::where("status", "=", "A")->orderBy("name","ASC")->get();
        if(auth()->user()->user_type_id!=2){
            $querys = "SELECT LO.*
  FROM MDA.L_LICENSE_OFFICE  LO
       LEFT JOIN MDA.EMPLOYEE_OFFICE_SETUP EOS
           ON EOS.OFFICE_ID = LO.OFFICE_ID
 WHERE EOS.EMP_ID = :EMP_ID";

            $offices = DB::select($querys, ['EMP_ID' => auth()->user()->employee->emp_id]);
        }else{
            $offices = '';
        }

        $slip_no = DB::selectOne('SELECT MDA.GENERATE_SLIP_NO(:user_id, :emp_id) AS SLIP_NO FROM DUAL', [ 'user_id' => Auth::user()->user_id, 'emp_id' => Auth::user()->emp_id]);
        $office_id = EmpOfficeSetup::where('emp_id', Auth::user()->emp_id)->pluck('office_id')->first();

        //$offices=LLicenseOffice::where("active_yn", "=", "Y")->orderBy("office_name","ASC")->get();
        return view("mda.cc_slip_generation",
            [
                "data"=>$data,
                "localVesselNames"=>$localVesselNames,
                "slip_types"=>$slip_types,
                'offices'=>$offices,
                'slip_no' => $slip_no->slip_no,
                'office_id' => $office_id
            ]);
    }

    public function slipGenerationCreatePort(Request $request)
    {
        $data = new CollectionSlip();
        $localVesselNames= LocalVessel::where("status", "=", "A")->orderBy("name","ASC")->get();
        $slip_types=LCollectionSlipType::where("status", "=", "A")->orderBy("name","ASC")->get();
        if(auth()->user()->user_type_id!=2){
            $querys = "SELECT LO.*
  FROM MDA.L_LICENSE_OFFICE  LO
       LEFT JOIN MDA.EMPLOYEE_OFFICE_SETUP EOS
           ON EOS.OFFICE_ID = LO.OFFICE_ID
 WHERE EOS.EMP_ID = :EMP_ID";

            $offices = DB::select($querys, ['EMP_ID' => auth()->user()->employee->emp_id]);
        }else{
            $offices = '';
        }

        $slip_no = DB::selectOne('SELECT MDA.GENERATE_SLIP_NO(:user_id, :emp_id) AS SLIP_NO FROM DUAL', [ 'user_id' => Auth::user()->user_id, 'emp_id' => Auth::user()->emp_id]);
        $office_id = EmpOfficeSetup::where('emp_id', Auth::user()->emp_id)->pluck('office_id')->first();

        //$offices=LLicenseOffice::where("active_yn", "=", "Y")->orderBy("office_name","ASC")->get();
        return view("mda.cc_slip_generation",
            [
                "data"=>$data,
                "localVesselNames"=>$localVesselNames,
                "slip_types"=>$slip_types,
                'offices'=>$offices,
                'slip_no' => $slip_no->slip_no,
                'office_id' => $office_id,
                'dues_type' => 1
            ]);
    }

    public function slipGenerationCreateRiver(Request $request)
    {
        $data = new CollectionSlip();
        $localVesselNames= LocalVessel::where("status", "=", "A")->orderBy("name","ASC")->get();
        $slip_types=LCollectionSlipType::where("status", "=", "A")->orderBy("name","ASC")->get();
        if(auth()->user()->user_type_id!=2){
            $querys = "SELECT LO.*
  FROM MDA.L_LICENSE_OFFICE  LO
       LEFT JOIN MDA.EMPLOYEE_OFFICE_SETUP EOS
           ON EOS.OFFICE_ID = LO.OFFICE_ID
 WHERE EOS.EMP_ID = :EMP_ID";

            $offices = DB::select($querys, ['EMP_ID' => auth()->user()->employee->emp_id]);
        }else{
            $offices = '';
        }

        $slip_no = DB::selectOne('SELECT MDA.GENERATE_SLIP_NO(:user_id, :emp_id) AS SLIP_NO FROM DUAL', [ 'user_id' => Auth::user()->user_id, 'emp_id' => Auth::user()->emp_id]);
        $office_id = EmpOfficeSetup::where('emp_id', Auth::user()->emp_id)->pluck('office_id')->first();

        //$offices=LLicenseOffice::where("active_yn", "=", "Y")->orderBy("office_name","ASC")->get();
        return view("mda.cc_slip_generation",
            [
                "data"=>$data,
                "localVesselNames"=>$localVesselNames,
                "slip_types"=>$slip_types,
                'offices'=>$offices,
                'slip_no' => $slip_no->slip_no,
                'office_id' => $office_id,
                'dues_type' => 2
            ]);
    }

    public function slipGenerationCreateBarge(Request $request)
    {
        $data = new CollectionSlip();
        $localVesselNames= LocalVessel::where("status", "=", "A")->orderBy("name","ASC")->get();
        $slip_types=LCollectionSlipType::where("status", "=", "A")->orderBy("name","ASC")->get();
        if(auth()->user()->user_type_id!=2){
            $querys = "SELECT LO.*
  FROM MDA.L_LICENSE_OFFICE  LO
       LEFT JOIN MDA.EMPLOYEE_OFFICE_SETUP EOS
           ON EOS.OFFICE_ID = LO.OFFICE_ID
 WHERE EOS.EMP_ID = :EMP_ID";

            $offices = DB::select($querys, ['EMP_ID' => auth()->user()->employee->emp_id]);
        }else{
            $offices = '';
        }

        $slip_no = DB::selectOne('SELECT MDA.GENERATE_SLIP_NO(:user_id, :emp_id) AS SLIP_NO FROM DUAL', [ 'user_id' => Auth::user()->user_id, 'emp_id' => Auth::user()->emp_id]);
        $office_id = EmpOfficeSetup::where('emp_id', Auth::user()->emp_id)->pluck('office_id')->first();

        //$offices=LLicenseOffice::where("active_yn", "=", "Y")->orderBy("office_name","ASC")->get();
        return view("mda.cc_slip_generation",
            [
                "data"=>$data,
                "localVesselNames"=>$localVesselNames,
                "slip_types"=>$slip_types,
                'offices'=>$offices,
                'slip_no' => $slip_no->slip_no,
                'office_id' => $office_id,
                'dues_type' => 3
            ]);
    }

    public function slipGenerationCreateLicense(Request $request)
    {
        $data = new CollectionSlip();
        $localVesselNames= LocalVessel::where("status", "=", "A")->orderBy("name","ASC")->get();
        $slip_types=LCollectionSlipType::where("status", "=", "A")->orderBy("name","ASC")->get();
        if(auth()->user()->user_type_id!=2){
            $querys = "SELECT LO.*
  FROM MDA.L_LICENSE_OFFICE  LO
       LEFT JOIN MDA.EMPLOYEE_OFFICE_SETUP EOS
           ON EOS.OFFICE_ID = LO.OFFICE_ID
 WHERE EOS.EMP_ID = :EMP_ID";

            $offices = DB::select($querys, ['EMP_ID' => auth()->user()->employee->emp_id]);
        }else{
            $offices = '';
        }

        $slip_no = DB::selectOne('SELECT MDA.GENERATE_SLIP_NO(:user_id, :emp_id) AS SLIP_NO FROM DUAL', [ 'user_id' => Auth::user()->user_id, 'emp_id' => Auth::user()->emp_id]);
        $office_id = EmpOfficeSetup::where('emp_id', Auth::user()->emp_id)->pluck('office_id')->first();

        //$offices=LLicenseOffice::where("active_yn", "=", "Y")->orderBy("office_name","ASC")->get();
        return view("mda.cc_slip_generation",
            [
                "data"=>$data,
                "localVesselNames"=>$localVesselNames,
                "slip_types"=>$slip_types,
                'offices'=>$offices,
                'slip_no' => $slip_no->slip_no,
                'office_id' => $office_id,
                'dues_type' => 4
            ]);
    }

    public function slipGenerationStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            /*$request->validate([
                'form_no' => 'required',
                'collection_date' => 'required',
                'slip_type_id' => 'required',
                'local_vessel_id' => 'required',
                'period_from' => 'required',
                'period_to' => 'required|after_or_equal:period_from',
                'port_dues_amount' => 'required',
                'river_dues_amount' => 'required',
                'vat_amount' => 'required',
            ],
                [
'local_vessel_id.required'=>'The local vessel field is required. '
                ]);*/
            $managerRes = $this->cashCollectionManager->cashCollectionCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function slipGenerationEdit($id)
    {
        $localVesselNames= LocalVessel::where("status", "=", "A")->orderBy("name","ASC")->get();
        $slip_types=LCollectionSlipType::where("status", "=", "A")->orderBy("name","ASC")->get();
        $offices=LLicenseOffice::where("active_yn", "=", "Y")->orderBy("office_name","ASC")->get();
        return view("mda.cc_slip_generation",
            [
                'data' => CollectionSlip::findOrFail($id),
                "localVesselNames"=>$localVesselNames,
                "slip_types"=>$slip_types,
                'offices'=>$offices
            ]);
//        return view('mda.cc_slip_generation', [
//            'data' => CollectionSlip::findOrFail($id)
//        ]);
    }

    public function slipGenerationUpdate(Request $request, $id)
    {
//        dd($request);
        if ($request->isMethod("POST")) {
            $request->validate([
//                'name' => 'required',
//                'status' => 'required',
            ]);
            $managerRes = $this->cashCollectionManager->cashCollectionCud('U', $request, $id);

            if ($managerRes['status']) {
                if($request->dues_select == 1) {
                    $dues_type = 'port';
                } elseif ($request->dues_select == 2) {
                    $dues_type = 'river';
                } elseif ($request->dues_select == 3) {
                    $dues_type = 'barge';
                } elseif ($request->dues_select == 4) {
                    $dues_type = 'license';
                }

                $message = redirect('/cc-slip-generation/'.$dues_type)->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function slipGenerationDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->cashCollectionManager->cashCollectionCud('D', $request, $id);
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

    public function slipGenerationDatatable(Request $request, $id)
    {
        //$dataTable = $this->cashCollectionManager->cashCollectionDatatable();
        $from = $request->get('from_date')?date('d-M-Y',strtotime($request->get('from_date'))):'';
        $to = $request->get('to_date')?date('d-M-Y',strtotime($request->get('to_date'))):'';
        $dues_type = $request->get('dues_type');

        if($from != 0 && $to != 0) {
            if (auth()->user()->user_type_id != 2) {
                $querys = "SELECT CS.*,
         LV.NAME      LOCAL_VESSEL_NAME,
         CST.NAME     AS SLIP_TYPE,
         LO.OFFICE_NAME,
         LV.OWNER_NAME
    FROM MDA.COLLECTION_SLIPS CS
         LEFT JOIN MDA.LOCAL_VESSELS LV ON LV.ID = CS.LOCAL_VESSEL_ID
         LEFT JOIN MDA.L_COLLECTION_SLIP_TYPES CST ON CST.ID = CS.SLIP_TYPE_ID
         LEFT JOIN MDA.L_LICENSE_OFFICE LO ON LO.OFFICE_ID = CS.OFFICE_ID
         LEFT JOIN MDA.EMPLOYEE_OFFICE_SETUP EOS
             ON EOS.OFFICE_ID = LO.OFFICE_ID
   WHERE EOS.EMP_ID = :EMP_ID
   AND CS.COLLECTION_DATE BETWEEN TO_DATE('$from', 'DD/MM/YYYY') AND TO_DATE('$to', 'DD/MM/YYYY')
   AND CS.DUES_SELECT = :DUES_TYPE
ORDER BY CS.CREATED_AT DESC";

                $dataTable = DB::select($querys, ['EMP_ID' => auth()->user()->employee->emp_id, 'DUES_TYPE' => $dues_type]);
            } else {
                $querys = "SELECT CS.*,
         LV.NAME      LOCAL_VESSEL_NAME,
         CST.NAME     AS SLIP_TYPE,
         LO.OFFICE_NAME,
         LV.OWNER_NAME
    FROM MDA.COLLECTION_SLIPS CS
         LEFT JOIN MDA.LOCAL_VESSELS LV ON LV.ID = CS.LOCAL_VESSEL_ID
         LEFT JOIN MDA.L_COLLECTION_SLIP_TYPES CST ON CST.ID = CS.SLIP_TYPE_ID
         LEFT JOIN MDA.L_LICENSE_OFFICE LO ON LO.OFFICE_ID = CS.OFFICE_ID
         LEFT JOIN MDA.EMPLOYEE_OFFICE_SETUP EOS
             ON EOS.OFFICE_ID = LO.OFFICE_ID
--WHERE EOS.EMP_ID = :EMP_ID
WHERE CS.COLLECTION_DATE BETWEEN TO_DATE('$from', 'DD/MM/YYYY') AND TO_DATE('$to', 'DD/MM/YYYY')
AND CS.DUES_SELECT = :DUES_TYPE
ORDER BY CS.CREATED_AT DESC";

                $dataTable = DB::select($querys, ['DUES_TYPE' => $dues_type]);
            }
        } else {
            if (auth()->user()->user_type_id != 2) {
                $querys = "SELECT CS.*,
         LV.NAME      LOCAL_VESSEL_NAME,
         CST.NAME     AS SLIP_TYPE,
         LO.OFFICE_NAME,
         LV.OWNER_NAME
    FROM MDA.COLLECTION_SLIPS CS
         LEFT JOIN MDA.LOCAL_VESSELS LV ON LV.ID = CS.LOCAL_VESSEL_ID
         LEFT JOIN MDA.L_COLLECTION_SLIP_TYPES CST ON CST.ID = CS.SLIP_TYPE_ID
         LEFT JOIN MDA.L_LICENSE_OFFICE LO ON LO.OFFICE_ID = CS.OFFICE_ID
         LEFT JOIN MDA.EMPLOYEE_OFFICE_SETUP EOS
             ON EOS.OFFICE_ID = LO.OFFICE_ID
   WHERE EOS.EMP_ID = :EMP_ID
   AND CS.DUES_SELECT = :DUES_TYPE
ORDER BY CS.CREATED_AT DESC";

                $dataTable = DB::select($querys, ['EMP_ID' => auth()->user()->employee->emp_id, 'DUES_TYPE' => $dues_type]);
            } else {
                $querys = "SELECT CS.*,
         LV.NAME      LOCAL_VESSEL_NAME,
         CST.NAME     AS SLIP_TYPE,
         LO.OFFICE_NAME,
         LV.OWNER_NAME
    FROM MDA.COLLECTION_SLIPS CS
         LEFT JOIN MDA.LOCAL_VESSELS LV ON LV.ID = CS.LOCAL_VESSEL_ID
         LEFT JOIN MDA.L_COLLECTION_SLIP_TYPES CST ON CST.ID = CS.SLIP_TYPE_ID
         LEFT JOIN MDA.L_LICENSE_OFFICE LO ON LO.OFFICE_ID = CS.OFFICE_ID
         LEFT JOIN MDA.EMPLOYEE_OFFICE_SETUP EOS
             ON EOS.OFFICE_ID = LO.OFFICE_ID
--WHERE EOS.EMP_ID = :EMP_ID
WHERE CS.DUES_SELECT = :DUES_TYPE
ORDER BY CS.CREATED_AT DESC";

                $dataTable = DB::select($querys, ['DUES_TYPE' => $dues_type]);
            }
        }
//dd($dataTable);
        return DataTables::of($dataTable)
//            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                if($data->status !='A'){
                    $optionHtml =  '<a href="' . route('cc-slip-generation-edit', $data->id) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                    if($id != $data->id ){
                        $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('cc-slip-generation-destroy', $data->id) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                    }
                }else{
                    $optionHtml = '';
                }
                $optionHtml .=  '<form class="d-inline" name="report-generator" id="report-generator" method="post" target="_blank" action="'.route('report', ['title' => 'bill']).'">
                            '.csrf_field().'
                            <input type="hidden" name="xdo" value="/~weblogic/MDA/RPT_DUES_COLLECTION_SLIPS.xdo"/>
                            <input type="hidden" name="type" id="type" value="pdf" />
                            <input type="hidden" name="p_id" id="p_id" value="'.$data->id.'" />
                            <button type="submit" class="btn btn-sm cursor-pointer p-0" data-toggle="tooltip" data-placement="top" title="Click to print report"><i class="bx bx-printer"></i></button>
                        </form>';

                return $optionHtml;
            })
            ->editColumn('period_form', function ($data) {
                return HelperClass::defaultDateTimeFormat($data->period_from, 'date');
            })
            ->editColumn('period_to', function ($data) {
                return  HelperClass::defaultDateTimeFormat($data->period_to, 'date');
            })
            ->editColumn('collection_date', function ($data) {
                return  HelperClass::defaultDateTimeFormat($data->collection_date, 'date');
            })
            ->editColumn('total', function ($data) {
                return   $data->river_dues_amount + $data->port_dues_amount + $data->license_bill_amount + $data->barge_fee_amount + $data->other_dues_amount + $data->vat_amount ;
            })
            ->editColumn('dues_amount', function ($data) {
                if($data->dues_select == 1) {
                    return   $data->port_dues_amount;
                } elseif ($data->dues_select == 2) {
                    return $data->river_dues_amount;
                } elseif ($data->dues_select == 3) {
                    return $data->barge_fee_amount;
                } elseif ($data->dues_select == 4) {
                    return $data->license_bill_amount;
                }
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 'A'){
                    return "Collected";
                }else{
                    return "Prepared";
                }
            })
            ->make(true);
    }

    function getVesselData(Request $request)
    {
        $vessel_id = $request->get('vessel_id');
        $data = LocalVessel::where("id", $vessel_id)->first();

        /*$msg = '<option value="">Select One</option>';
        foreach ($list as $data) {
            $msg .= '<option value="' . $data->location_id . '">' . $data->location . '</option>';
        }*/
        return $data->grt;
    }

    /************End collection slip types*************/

}
