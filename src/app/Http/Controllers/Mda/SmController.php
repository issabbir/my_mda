<?php


namespace App\Http\Controllers\Mda;


use App\Contracts\Mda\SmContract;
use App\Entities\Mda\CpaPilot;
use App\Entities\Mda\Employee;
use App\Entities\Mda\MooringVisit;
use App\Entities\Mda\CpaVessel;
use App\Entities\Mda\LocalVessel;
use App\Entities\Mda\SwingMooring;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\True_;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Services\DataTable;

class  SmController extends Controller
{
    private $smManager;
    public function __construct(SmContract $smManager)
    {
        $this->smManager = $smManager;
    }
    /************ start Swing Moorings*************/
    public function smCreate(Request $request)
    {
        $data = new SwingMooring();

        return view("mda.sm_registration", ["data"=>$data]);
    }

    public function smStore(Request $request)
    {
        if ($request->isMethod("post")){
            $request->validate([
                    "name" => "required",
                    "serial_no" => "required",
                    "status" => "required"
                ]);

            $managerResponse = $this->smManager->swingMooringsCud("I", $request);

            if ($managerResponse["status"]){
                $message = redirect()->back()->with("success", $managerResponse["status_message"]);
            }else{
                $message = redirect()->back()->with("error", $managerResponse["status_message"])->withInput();
            }

            return $message;
        }
    }

    public function smEdit($id)
    {
        return view('mda.sm_registration', [
            'data' => SwingMooring::findOrFail($id)
        ]);

    }

    public function smUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")){
            $request->validate([
                "name" => "required",
                "serial_no" => "required",
                "status" => "required"
            ]);

            $managerResponse = $this->smManager->swingMooringsCud("U", $request, $id);

            if ($managerResponse["status"]){
                $message = redirect()->back()->with("success", $managerResponse["status_message"]);
            }else{
                $message = redirect()->back()->with("error", $managerResponse["status_message"])->withInput();
            }

            return $message;
        }
    }

    public function smDestroy(Request $request, $id)
    {
        if ($id){
            $response = $this->smManager->swingMooringsCud("D", $request, $id);

            $res = [
                "success" => ($response["status"]) ? True: false,
                "message" => $response["status_message"]
            ];
        }else{
            $res = [
                "success" => false,
                "message" => "Invalid require data"
            ];
        }

        return $res;
    }

    public function smDatatable($id)
    {
        $datatable = $this->smManager->swingMooringsDatatable();

        return DataTables::of($datatable)
            ->addIndexColumn()
            ->editColumn('created_at', function ($data){
                return isset($data['created_at'])? HelperClass::defaultDateTimeFormat($data['created_at'], 'datetime'):'';
            })
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('sm-registration-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('sm-registration-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data){
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }
    /************Swing Moorings end*************/

    /************ start  Moorings Visits/ licences duty entry  *************/
    public function smLdeCreate(Request $request)
    {
        $data = new MooringVisit();
        $cpaVesselNames = CpaVessel::where("status", "=", "A")->orderBy("name","ASC")->get();
        $localVesselNames= LocalVessel::where("status", "=", "A")->orderBy("name","ASC")->get();
        $swingMooringsNames=SwingMooring::where("status", "=", "A")->orderBy("name","ASC")->get();

        return view("mda.sm_license_duty_entry",
            [
                "data"=>$data,
                "cpaVesselNames"=>$cpaVesselNames,
                "localVesselNames"=>$localVesselNames,
                "swingMooringsNames"=>$swingMooringsNames
            ]);
    }

    public function getLastAgent(Request $request)
    {//dd($request->all());
        $agency_name = null;
        $agency_id = null;
        $searchTerm = $request->get('vessel_id');
        $sql = "SELECT * FROM MDA.LOCAL_VESSELS
where ID = :VESSEL_ID";
        $agency = db::selectOne($sql,['VESSEL_ID' => $searchTerm]);
        if(isset($agency) && !empty($agency)){
            $agency_id = $agency->agency_id;
            $agency_name = $agency->owner_name;
        }

        return $agency_id.'+'.$agency_name;
    }

    public function  smLdeStore(Request $request)
    {
//        dd($request);
        if ($request->isMethod("post")){
            $request->validate([
                "visit_date" => "required",
                //"cpa_vessel" => "required",
                "local_vessel" => "required",
                //"sl_no" => "required",
                "swing_moorings" => "required",
                //"lm_rep" => "required"
            ]);

            $managerResponse = $this->smManager->mooringVisitsCud("I", $request);

            if ($managerResponse["status"]){
                $message = redirect()->back()->with("success", $managerResponse["status_message"]);
            }else{
                $message = redirect()->back()->with("error", $managerResponse["status_message"])->withInput();
            }

            return $message;
        }
    }

    public function smLdeEdit($id)
    {
        $cpaVesselNames = CpaVessel::where("status", "=", "A")->orderBy("name","ASC")->get();
        $localVesselNames= LocalVessel::where("status", "=", "A")->orderBy("name","ASC")->get();
        $swingMooringsNames=SwingMooring::where("status", "=", "A")->orderBy("name","ASC")->get();
        $data = MooringVisit::where('id','=',$id)->with('employee')->get()[0];

        return view("mda.sm_license_duty_entry",
            [
               'data' => $data,
               "cpaVesselNames"=>$cpaVesselNames,
               "localVesselNames"=>$localVesselNames,
               "swingMooringsNames"=>$swingMooringsNames
            ]);
    }

    public function smLdeUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")){
            $request->validate([
                "visit_date" => "required",
                //"cpa_vessel" => "required",
                "local_vessel" => "required",
               // "sl_no" => "required",
                "swing_moorings" => "required",
                //"lm_rep" => "required"
            ]);

            $managerResponse = $this->smManager->mooringVisitsCud("U", $request, $id);
            if ($managerResponse["status"]){
                $message = redirect()->back()->with("success", $managerResponse["status_message"]);
            }else{
                $message = redirect()->back()->with("error", $managerResponse["status_message"])->withInput();
            }
            return $message;
        }
    }

    public function smLdeDestroy(Request $request, $id)
    {
        if ($id){
            $response = $this->smManager->mooringVisitsCud("D", $request, $id);
            $res = [
                "success" => ($response["status"]) ? True: false,
                "message" => $response["status_message"]
            ];
        }else{
            $res = [
                "success" => false,
                "message" => "Invalid require data"
            ];
        }
        return $res;
    }

    public function  smLdeDatatable(Request $request, $id)
    {
        $from = $request->get('from_date')?date('d-M-Y',strtotime($request->get('from_date'))):'';
        $to = $request->get('to_date')?date('d-M-Y',strtotime($request->get('to_date'))):'';
        if($from != 0 && $to != 0){
            $querys = "SELECT MV.ID,MV.STATUS, MV.VISIT_DATE, CPV.NAME CPA_VESSEL, LV.NAME LOCAL_VESSEL, SM.NAME SWING_MOORING_NAME FROM MDA.MOORING_VISITS MV
LEFT JOIN MDA.CPA_VESSELS CPV ON CPV.ID = MV.CPA_VESSEL_ID
LEFT JOIN MDA.LOCAL_VESSELS LV ON LV.ID = MV.LOCAL_VESSEL_ID
LEFT JOIN MDA.SWING_MOORINGS SM ON MV.SWING_MOORING_ID = SM.ID
WHERE MV.VISIT_DATE BETWEEN TO_DATE('$from', 'DD/MM/YYYY') AND TO_DATE('$to', 'DD/MM/YYYY')
And MV.STATUS !='D'
ORDER BY MV.CREATED_AT DESC";
        }
        else{
            $querys = "SELECT MV.ID,MV.STATUS, MV.VISIT_DATE, CPV.NAME CPA_VESSEL, LV.NAME LOCAL_VESSEL, SM.NAME SWING_MOORING_NAME FROM MDA.MOORING_VISITS MV
LEFT JOIN MDA.CPA_VESSELS CPV ON CPV.ID = MV.CPA_VESSEL_ID
LEFT JOIN MDA.LOCAL_VESSELS LV ON LV.ID = MV.LOCAL_VESSEL_ID
LEFT JOIN MDA.SWING_MOORINGS SM ON MV.SWING_MOORING_ID = SM.ID
WHERE MV.STATUS !='D'
ORDER BY MV.CREATED_AT DESC";
        }

        //$datatable = $this->smManager->mooringVisitsDatatable($request);

        //dd($querys);
        $datatable = db::select($querys);
        //dd($datatable);
        /*return DataTables::of($datatable)
            ->addIndexColumn()
            ->editColumn('visit_date', function ($data){
                return isset($data['visit_date'])? HelperClass::defaultDateTimeFormat($data['visit_date'], 'date'):'';
            })
            ->addColumn('action', function ($data) use($id) {

                if ($data["status"] == "P" ){
                    $optionHtml =  '<a href="' . route('sm-license-duty-entry-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                    if($id != $data['id'] ){
                        $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('sm-license-duty-entry-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                    }
                }else{
                    $optionHtml="";
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data){
                return HelperClass::getStatus(isset($data['status'])?$data['status']:'');
            })
            ->make(true);*/
        return DataTables::of($datatable)
            ->addIndexColumn()
            ->editColumn('visit_date', function ($data){
                return isset($data->visit_date)? HelperClass::defaultDateTimeFormat($data->visit_date, 'date'):'';
            })
            ->addColumn('action', function ($data) use($id) {

                if ($data->status == "P" ){
                    $optionHtml =  '<a href="' . route('sm-license-duty-entry-edit', $data->id) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                    if($id != $data->id ){
                        $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('sm-license-duty-entry-destroy', $data->id) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                    }
                }else{
                    $optionHtml="";
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data){
                return HelperClass::getStatus(isset($data->status)?$data->status:'');
            })
            ->make(true);
    }

    /************ Moorings Visits end *************/

    /************sm  Inspector approvel  *************/
    public function smInsApproveCreate(Request $request)
    {
        $data = new MooringVisit();
        $cpaVesselNames = CpaVessel::where("status", "=", "A")->orderBy("name","ASC")->get();
        $localVesselNames= LocalVessel::where("status", "=", "A")->orderBy("name","ASC")->get();
        $swingMooringsNames=SwingMooring::where("status", "=", "A")->orderBy("name","ASC")->get();
        return view("mda.sm_inspector_approval",
            [
                "data"=>$data,
                "cpaVesselNames"=>$cpaVesselNames,
                "localVesselNames"=>$localVesselNames,
                "swingMooringsNames"=>$swingMooringsNames
            ]);
    }

    public function  smInsApproveStore(Request $request)
    {
        if ($request->isMethod("post")){
            $request->validate([
                "visit_date" => "required",
                "cpa_vessel" => "required",
                "local_vessel" => "required",
                "sl_no" => "required",
                "swing_moorings" => "required",
                "status" => "required",
                "lm_rep" => "required"
            ]);

            $managerResponse = $this->smManager->mooringVisitsCud("I", $request);

            if ($managerResponse["status"]){
                $message = redirect()->back()->with("success", $managerResponse["status_message"]);
            }else{
                $message = redirect()->back()->with("error", $managerResponse["status_message"])->withInput();
            }

            return $message;
        }
    }

    public function smInsApproveEdit($id)
    {
        $cpaVesselNames = CpaVessel::where("status", "=", "A")->orderBy("name","ASC")->get();
        $localVesselNames= LocalVessel::where("status", "=", "A")->orderBy("name","ASC")->get();
        $swingMooringsNames=SwingMooring::where("status", "=", "A")->orderBy("name","ASC")->get();
        return view("mda.sm_inspector_approval",
            [
                'data' => MooringVisit::where('id','=',$id)->with('employee')->get()[0],
                "cpaVesselNames"=>$cpaVesselNames,
                "localVesselNames"=>$localVesselNames,
                "swingMooringsNames"=>$swingMooringsNames
            ]);
    }

    public function smInsApproveUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")){
            $request->validate([
                "visit_date" => "required",
                "cpa_vessel" => "required",
                "local_vessel" => "required",
                "sl_no" => "required",
                "swing_moorings" => "required",
                "status" => "required",
                "lm_rep" => "required"
            ]);

            $managerResponse = $this->smManager->mooringVisitsCud("U", $request, $id);
            if ($managerResponse["status"]){
                $message = redirect()->back()->with("success", $managerResponse["status_message"]);
            }else{
                $message = redirect()->back()->with("error", $managerResponse["status_message"])->withInput();
            }
            return $message;
        }
    }

    public function smInsApproveDestroy(Request $request, $id)
    {
        if ($id){
            $response = $this->smManager->mooringVisitsCud("D", $request, $id);
            $res = [
                "success" => ($response["status"]) ? True: false,
                "message" => $response["status_message"]
            ];
        }else{
            $res = [
                "success" => false,
                "message" => "Invalid require data"
            ];
        }
        return $res;
    }

    public function  smInsApproveDatatable(Request $request, $id)
    {
        $datatable = $this->smManager->mooringVisitsDatatable($request);

        return DataTables::of($datatable)
            ->addIndexColumn()
            ->editColumn('visit_date', function ($data){
                return isset($data['visit_date'])? HelperClass::defaultDateTimeFormat($data['visit_date'], 'date'):'';
            })
            ->addColumn('action', function ($data) use($id) {
                if ($data["status"] == "P" ){
                    $optionHtml =  '<a href="' . route('sm-inspector-approval-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                    if($id != $data['id'] ){
                        $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('sm-inspector-approval-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';

                        $optionHtml .= '<span data-mooringvisitid="'.$data['id'].'"  onclick="approve_disapprove(this)" id="approveInspect'.$data['id'].'"><i class="bx bx-hourglass cursor-pointer"></i> </span>';

                    }
                }else{
                    $optionHtml="";
                }

                return $optionHtml;
            })
            ->editColumn('status', function ($data){
                return HelperClass::getStatus(isset($data['status'])?$data['status']:'');
            })
            ->make(true);
    }

    public function smInsApproval(Request $request)
    {
        $visitId = $request->get("mooring_visit_id");

        if ($visitId){
            $response = $this->smManager->mooringVisitChangeStatus("U", $request, $visitId);

            $res = [
                "success" => ($response["status"]) ? True: false,
                "message" => $response["status_message"],
            ];
        }else{
            $res = [
                "success" => false,
                "message" => "Invalid require data"
            ];
        }

        return $res;
    }
    /************ Moorings Visits end *************/

    public function pilotList(Request $request)
    {
        /*$employee = [];
        if ($request->has('q')){
            $search = $request->get('q');
            $employee = Employee::select("emp_id","emp_name")->where('emp_name','LIKE',"%$search%")->orderBy('emp_name','ASC')->get();
        }
        return response()->json($employee);*/

        $search = strtoupper($request->get('q', ''));
        $employee = Employee::select("emp_id","emp_name")->where('emp_name','LIKE',"%$search%")->orderBy('emp_name','ASC')->get();
        return response()->json($employee);
    }

    public function pilotDtl(Request $request)
    {
        $search = $request->get('pid','');
        $employeeData = DB::selectOne("select emp.emp_name, dep.department_name, des.designation from pmis.employee emp
left join pmis.l_department dep on (emp.dpt_department_id = dep.department_id)
left join pmis.l_designation des on (emp.designation_id = des.designation_id)
where emp.emp_id =".$search);
        return response()->json($employeeData);
    }




}
