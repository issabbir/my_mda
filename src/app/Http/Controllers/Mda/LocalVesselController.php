<?php

namespace App\Http\Controllers\Mda;
use App\Contracts\Mda\LocalVesselContract;
use App\Entities\Mda\LocalVessel;
use App\Entities\Mda\MediaFile;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LocalVesselController extends Controller
{
    public $localVesselManager;
    public function __construct(LocalVesselContract $localVesselManager)
    {
        $this->localVesselManager = $localVesselManager;
    }

    /************Start Local Vessel*************/
    public function localVesselCreate(Request $request)
    {
        $querys = "SELECT * FROM VSL.SECDBMS_L_AGENCY
WHERE AGENCY_TYPE_NAME = 'LOCAL VESSEL'
--AND CUSTOMER_ID IS NOT NULL
ORDER BY AGENCY_NAME ASC" ;
        $agencyList = DB::select($querys);
        $data = new LocalVessel();
        return view('mda.local_vessel', ['data'=>$data,'agencyList'=>$agencyList]);
    }

    public function  localVesselStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
                //'reg_file' => 'mimes:pdf,doc,docx|max:102400'
            ]);

            /*$file = $request->file("reg_file");
            $destination = "assets/local_vessel/";
            $renamedFile = date("Ymdis").".".$file->getClientOriginalExtension();

            $file->move($destination, $renamedFile);

            $fileNameWithPath = $destination.$renamedFile;
            $request["file"] = $fileNameWithPath;*/
            //dd($request);

            $managerRes = $this->localVesselManager->localVesselCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function  localVesselEdit($id)
    {
        $querys = "SELECT * FROM VSL.SECDBMS_L_AGENCY
WHERE AGENCY_TYPE_NAME = 'LOCAL VESSEL'
--AND CUSTOMER_ID IS NOT NULL
ORDER BY AGENCY_NAME ASC" ;
        $agencyList = DB::select($querys);
       //dd(LocalVessel::where("id","=",$id)->with("vessel_file")->first());
        return view('mda.local_vessel', [
            'data' => LocalVessel::where("id","=",$id)->with("vessel_file")->first(),'agencyList'=>$agencyList
        ]);
    }

    public function  localVesselUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
            ]);
            $managerRes = $this->localVesselManager->localVesselCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function  localVesselDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->localVesselManager->localVesselCud('D', $request, $id);
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

    public function localVesselDatatable($id)
    {
        $dataTable = $this->localVesselManager->localVesselDatatable();
//dd($dataTable[0]->agent);
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('agency_name', function ($data) {
                if($data->agent) {
                    return $data->agent['agency_name'];
                }
                return '';
            })
            ->addColumn('agency_address', function ($data) {
                if($data->agent) {
                    return $data->agent['address'];
                }
                return '';
            })
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('local-vessel-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('local-vessel-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('reg_exp_date', function ($data){
                if($data['reg_exp_date']!=null){
                    return date('d-m-Y', strtotime($data['reg_exp_date']));
                }else{
                    return '--';
                }

            })
            ->editColumn('status', function ($data) {
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }

    public function downloadAfile($id)
    {
        $file = MediaFile::where("id","=",$id)->get();

        $content =  base64_decode($file[0]->files);
        return response()->make($content, 200, [
            'Content-Type' => $file[0]->file_type,
            'Content-Disposition' => 'attachment;filename="document.'.$file[0]->file_type.'"'
        ]);
    }

    public function getAgentInfo(Request $request)
    {
        $querys = "SELECT * FROM VSL.SECDBMS_L_AGENCY
WHERE AGENCY_ID = :AGENCY_ID" ;
        $agency = DB::selectOne($querys, ['AGENCY_ID' => $request->get('agent_id')]);
        return $agency->address;
    }

    /************End local vessel*************/



}
