<?php

namespace App\Http\Controllers\Mda;

use App\Contracts\Mda\BsContract;
use App\Entities\Mda\BerthingSchedule;
use App\Entities\Mda\CpaPilot;
use App\Entities\Mda\Employee;
use App\Entities\Mda\ForeignVessel;
use App\Entities\Mda\CpaCargo;
use App\Entities\Mda\AreaInfo;
use App\Entities\Mda\LPilotageType;
use App\Entities\Mda\LTidalCycle;
use App\Entities\VSL\JettyList;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Services\MDA\SMSService;
use App\Services\MDA\EmailService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use function GuzzleHttp\Promise\all;

class BsController extends Controller
{
    public $bsManager;
    public function __construct(BsContract $bsManager)
    {
        $this->bsManager = $bsManager;
    }

    public function bsCreate(Request $request)
    {
        $data = new BerthingSchedule();
        return view("mda.berthing_schedule",
            [
                "data"=>$data,
                "vesselNames"=>ForeignVessel::orderBy("name","ASC")->select('name','reg_no','id','arrival_date')->get(),
                "pilotage_types"=>LPilotageType::where("status", "=", "A")->orderBy("id","ASC")->get(),
                "cpa_cargos"=>CpaCargo::where("status", "=", "A")->orderBy("name","ASC")->get(),
//                "jetty_types"=>AreaInfo::where("type_id", "=", "2")->orderBy("name","ASC")->get(),
                "jetty_types"=>JettyList::where("status", "A")->get(),
                "tidal_cycles"=>LTidalCycle::where("active_yn","=","Y")->orderBy("tidal_cycle_name","ASC")->get()
            ]);
    }

    public function bsStore(Request $request)
    { //dd($request->all());
        if ($request->isMethod("POST")) {
            $request->validate([
                'vessel_id' => 'required',
                'local_agent' => 'required',
                'arrival_at' => 'required',
                'pilotage_type_id' => 'required',
                'berthing_at' => 'exclude_if:pilotage_type_id, 2|required|after_or_equal:arrival_at',
                'shifting_at' => 'exclude_unless:pilotage_type_id,2|required|after_or_equal:arrival_at',
//                'pilotage_time' => 'required',
                'leaving_at' => 'required|after_or_equal:arrival_at',
                'jetty_id' => 'required',
//                'cargo_id' => 'required'
            ]);


            $managerRes = $this->bsManager->bsCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function bsEdit($id)
    {
//        dd(BerthingSchedule::findOrFail($id));
        return view("mda.berthing_schedule",
            [
                'data' => BerthingSchedule::findOrFail($id),
                "vesselNames"=>ForeignVessel::orderBy("name","ASC")->select('name','reg_no','id','arrival_date')->get(),
                "pilotage_types"=>LPilotageType::where("status", "=", "A")->orderBy("id","ASC")->get(),
                "cpa_cargos"=>CpaCargo::where("status", "=", "A")->orderBy("name","ASC")->get(),
//                "jetty_types"=>AreaInfo::where("type_id", "=", "2")->orderBy("name","ASC")->get(),
                "jetty_types"=>JettyList::where("status", "A")->get(),
                "tidal_cycles"=>LTidalCycle::where("active_yn","=","Y")->orderBy("tidal_cycle_name","ASC")->get()

            ]);

    }

    public function bsUpdate(Request $request, $id)
    { //dd($request->all());
        if ($request->isMethod("PUT")) {
            $request->validate([
                'vessel_id' => 'required',
                'local_agent' => 'required',
                'arrival_at' => 'required',
                'pilotage_type_id' => 'required',
                'berthing_at' => 'exclude_if:pilotage_type_id, 2|required|after_or_equal:arrival_at',
                'shifting_at' => 'exclude_unless:pilotage_type_id,2|required|after_or_equal:arrival_at',
//                'pilotage_time' => 'required',
                'leaving_at' => 'required|after_or_equal:arrival_at',
                'jetty_id' => 'required',
       //         'cargo_id' => 'required'
            ]);
            $managerRes = $this->bsManager->bsCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function bsDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->bsManager->bsCud('D', $request, $id);
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

    public function berthDatatable($id)
    {
        $dataTable = $this->bsManager->bsDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('berthing-schedule-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('berthing-schedule-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn("jetty", function ($data){
                return (isset($data["jetty"]["name"])) ? $data["jetty"]["name"] : "";
            })
            ->editColumn("vessel_name", function ($data) {
                return (isset($data["foreign_vessel"]["name"])) ? ($data["foreign_vessel"]["name"]."(".$data["foreign_vessel"]["reg_no"].")") : "";
            })
            ->editColumn("local_agent", function ($data){
                return (isset($data["local_agent"])) ? $data["local_agent"] : "";
            })
            ->editColumn('arrival_at', function ($data) {
                return isset($data['arival_at'])?HelperClass::defaultDateTimeFormat($data['arival_at'], 'date'):'';
            })
            ->editColumn('pilotage_time', function ($data) {
                return  $data['pilotage_time'];
            })
            ->editColumn('pilotage_type', function ($data) {
                return (isset($data["pilotage_type"]["name"])) ? $data["pilotage_type"]["name"] : "";
            })
            ->editColumn('cpa_cargo', function ($data) {
                return (isset($data["cpa_cargo"]["name"])) ? $data["cpa_cargo"]["name"] : "";
            })
            ->editColumn('berthing_date', function ($data) {
                if (isset($data['berthing_at'])){
                    return  HelperClass::defaultDateTimeFormat($data['berthing_at'], 'date');
                }else{
                    return "---";
                }
            })
            ->editColumn('shifting_date', function ($data) {
                if (isset($data['shifting_at'])){
                    return  HelperClass::defaultDateTimeFormat($data['shifting_at'], 'date');
                }else{
                    return "---";
                }
            })
            ->editColumn('leaving_at', function ($data) {
                return  HelperClass::defaultDateTimeFormat($data['leaving_at'], 'date');
            })
            ->make(true);
    }
    public function foreignVesselData($id)
    {
        $vesselsDetails = ForeignVessel::where("id","=",$id)->get();
        return response()->json(["status"=>"200", "vesselDetail" => $vesselsDetails[0]]);
    }

    //Verify BS start
    public function verifyBsCreate()
    {
        $data = new BerthingSchedule();
        return view("mda.berthing_schedule_verify",
            [
                "data"=>$data,
                "vesselNames"=>ForeignVessel::orderBy("name","ASC")->select('name','reg_no','id','arrival_date')->get(),
                "pilotage_types"=>LPilotageType::where("status", "=", "A")->orderBy("id","ASC")->get(),
                "cpa_cargos"=>CpaCargo::where("status", "=", "A")->orderBy("name","ASC")->get(),
//                "jetty_types"=>AreaInfo::where("type_id", "=", "2")->orderBy("name","ASC")->get(),
                "jetty_types"=>JettyList::where("status", "A")->get(),
                "pilotList" => CpaPilot::orderBy("name", "ASC")->get(),
                "tidal_cycles"=>LTidalCycle::where("active_yn","=","Y")->orderBy("tidal_cycle_name","ASC")->get()
            ]);
    }

    public function verifyBsStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'vessel_id' => 'required',
                'local_agent' => 'required',
                'arrival_at' => 'required',
                'pilotage_type_id' => 'required',
                'berthing_at' => 'exclude_if:pilotage_type_id, 2|required|after_or_equal:arrival_at',
                'shifting_at' => 'exclude_unless:pilotage_type_id,2|required|after_or_equal:arrival_at',
                //'pilotage_time' => 'required',
                'leaving_at' => 'required|after_or_equal:arrival_at',
                'jetty_id' => 'required',
                'cargo_id' => 'required',
//                'import_disch' => 'required',
//                'b_on_board' => 'required',
//                't_on_board' => 'required',
//                'exp_lefted' => 'required',
                'pilotage_schedule_start' => 'required',
                'pilotage_schedule_end' => 'required|after_or_equal:pilotage_schedule_start',
                'pilot_id' => 'required',
                'notification_type' => 'required'
            ]);
            $managerRes = $this->bsManager->bsCud('I', $request);

            if ($managerRes['status']) {

                $employee = CpaPilot::where('id','=', $request->get('pilot_id'))->get()[0];
                $smsService = new SMSService();
                $emailService = new EmailService();
                try {
                    switch ($request->get('notification_type')){
                        case 'SMS':
                            if($employee->mobile != ""){
                                if(env('BS_SMS',0) == 1){
                                    $number = $employee->mobile;
                                    $msg = "Hello";
                                    $smsService->sendSMS($number, $msg);
                                }

                            }
                            break;
                        case 'EMAIL':
                            if ($employee->email != ""){
                                if(env('BS_EMAIL',0) == 1){
                                    $email = [$employee->email];
                                    $sub = "";
                                    $msg = "HEllo"; //Email format will be given by Mosiuzzaman vai

                                    $emailService->sendEmail($type = 'HTML', '', $employee->name, $sub, $email, '', '', $msg, '', '');
                                }
                            }
                            break;
                        case 'BOTH':
                            if($employee->mobile != ""){
                                if(env('BS_SMS',0) == 1){
                                    $number = $employee->mobile;
                                    $msg = "Hello";
                                    $smsService->sendSMS($number, $msg);
                                }
                            }

                            if ($employee->email != ""){
                                if(env('BS_EMAIL',0) == 1){
                                    $email = [$employee->email];
                                    $sub = "";
                                    $msg = "HEllo"; //Email format will be given by Mosiuzzaman vai
                                    $emailService->sendEmail($type = 'TEXT', '', $employee->name, $sub, $email, '', '', $msg, '', '');
                                }
                            }
                            break;
                        default:
                            break;
                    }
                }catch (\Exception $e){
                    $managerRes['status_message'] = $managerRes['status_message']." ".$e->getMessage();
                }

                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function verifyBsEdit($id)
    {
        return view("mda.berthing_schedule_verify",
            [
                'data' => BerthingSchedule::findOrFail($id),
                "vesselNames"=>ForeignVessel::orderBy("name","ASC")->select('name','reg_no','id','arrival_date')->get(),
                "pilotage_types"=>LPilotageType::where("status", "=", "A")->orderBy("id","ASC")->get(),
                "cpa_cargos"=>CpaCargo::where("status", "=", "A")->orderBy("name","ASC")->get(),
//                "jetty_types"=>AreaInfo::where("type_id", "=", "2")->orderBy("name","ASC")->get(),
                "jetty_types"=>JettyList::where("status", "A")->get(),
                "pilotList" => CpaPilot::orderBy("name", "ASC")->get(),
                "tidal_cycles"=>LTidalCycle::where("active_yn","=","Y")->orderBy("tidal_cycle_name","ASC")->get()
            ]);
    }

    public function verifyBsUpdate(Request $request, $id)
    { //dd($request);
        if ($request->isMethod("PUT")) {
            $request->validate([
                'vessel_id' => 'required',
                'local_agent' => 'required',
                'arrival_at' => 'required',
                'pilotage_type_id' => 'required',
                'berthing_at' => 'exclude_if:pilotage_type_id, 2|required|after_or_equal:arrival_at',
                'shifting_at' => 'exclude_unless:pilotage_type_id,2|required|after_or_equal:arrival_at',
                'pilotage_time' => 'required',
                'leaving_at' => 'required|after_or_equal:arrival_at',
                'jetty_id' => 'required',
//                'cargo_id' => 'required',
//                'import_disch' => 'required',
//                'b_on_board' => 'required',
//                't_on_board' => 'required',
//                'exp_lefted' => 'required',
//                'pilotage_schedule_start' => 'required',
//                'pilotage_schedule_end' => 'required|after_or_equal:pilotage_schedule_start',
                'pilot_id' => 'required',
                'notification_type' => 'required'
            ]);

            $managerRes = $this->bsManager->bsCud('U', $request, $id);

            if ($managerRes['status']) {

                $employee = CpaPilot::where('id','=', $request->get('pilot_id'))->get()[0];
                $smsService = new SMSService();
                $emailService = new EmailService();
                try {
                    switch ($request->get('notification_type')){
                        case 'SMS':
                            if($employee->mobile != ""){
                                if(env('BS_SMS',0) == 1){
                                    $number = $employee->mobile;
                                    $msg = "Hello";
                                    $smsService->sendSMS($number, $msg);
                                }

                            }
                            break;
                        case 'EMAIL':
                            if ($employee->email != ""){
                                if(env('BS_EMAIL',0) == 1){
                                    $email = [$employee->email];
                                    $sub = "";
                                    $msg = "HEllo"; //Email format will be given by Mosiuzzaman vai

                                    $emailService->sendEmail($type = 'HTML', '', $employee->name, $sub, $email, '', '', $msg, '', '');
                                }
                            }
                            break;
                        case 'BOTH':
                            if($employee->mobile != ""){
                                if(env('BS_SMS',0) == 1){
                                    $number = $employee->mobile;
                                    $msg = "Hello";
                                    $smsService->sendSMS($number, $msg);
                                }
                            }

                            if ($employee->email != ""){
                                if(env('BS_EMAIL',0) == 1){
                                    $email = [$employee->email];
                                    $sub = "";
                                    $msg = "HEllo"; //Email format will be given by Mosiuzzaman vai
                                    $emailService->sendEmail($type = 'TEXT', '', $employee->name, $sub, $email, '', '', $msg, '', '');
                                }
                            }
                            break;
                        default:
                            break;
                    }

                }catch (\Exception $e){
                    $managerRes['status_message'] = $managerRes['status_message']." ".$e->getMessage();
                }
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function verifyBsDatatable($id)
    {
        $dataTable = $this->bsManager->bsDatatable();

        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('verify-bs-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('berthing-schedule-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn("jetty", function ($data){
                return (isset($data["jetty"]["name"])) ? $data["jetty"]["name"] : "";
            })
            ->editColumn("vessel_name", function ($data) {
                return (isset($data["foreign_vessel"]["name"])) ? ($data["foreign_vessel"]["name"]."(".$data["foreign_vessel"]["reg_no"].")") : "";
            })
            ->editColumn("local_agent", function ($data){
                return (isset($data["local_agent"])) ? $data["local_agent"] : "";
            })
            ->editColumn('cpa_pilot', function ($data) {
                return (isset($data["cpa_pilot"]['name'])) ? $data["cpa_pilot"]['name'] : "";
            })
            ->editColumn('pilotage_time', function ($data) {
                return  $data['pilotage_time'];
            })
            ->editColumn('pilotage_type', function ($data) {
                return (isset($data["pilotage_type"]["name"])) ? $data["pilotage_type"]["name"] : "";
            })
            ->editColumn('cpa_cargo', function ($data) {
                return (isset($data["cpa_cargo"]["name"])) ? $data["cpa_cargo"]["name"] : "";
            })
            ->editColumn('berthing_date', function ($data) {
                if (isset($data['berthing_at'])){
                    return  HelperClass::defaultDateTimeFormat($data['berthing_at'], 'date');
                }else{
                    return "---";
                }
            })
            ->editColumn('shifting_date', function ($data) {
                if (isset($data['shifting_at'])){
                    return  HelperClass::defaultDateTimeFormat($data['shifting_at'], 'date');
                }else{
                    return "---";
                }
            })
            ->editColumn('leaving_at', function ($data) {
                return  HelperClass::defaultDateTimeFormat($data['leaving_at'], 'date');
            })
            ->make(true);
    }

}
