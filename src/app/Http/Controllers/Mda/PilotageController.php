<?php


namespace App\Http\Controllers\Mda;


use App\Contracts\Mda\PilotageContract;
use App\Entities\Mda\AreaInfo;
use App\Entities\Mda\CpaPilot;
use App\Entities\Mda\ForeignVessel;
use App\Entities\Mda\LPilotageScheduleType;
use App\Entities\Mda\LPilotageType;
use App\Entities\Mda\LPilotageWorkLocation;
use App\Entities\Mda\LVesselCondition;
use App\Entities\Mda\LVesselWorkingType;
use App\Entities\Mda\MediaFile;
use App\Entities\Mda\Pilotage;
use App\Entities\Mda\TugsRegistration;
use App\Entities\VSL\JettyList;
use App\Entities\VSL\RegistrationInfo;
use App\Enums\BillTypeEnum;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Rules\DateTimeRange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PilotageController extends Controller
{
    protected $pilotageManager;

    public function __construct(PilotageContract $pilotageManager)
    {
        $this->pilotageManager = $pilotageManager;
    }

    public function pilotageCreate()
    {
        $data = new Pilotage();

//        $vesselName = ForeignVessel::orderBy("name", "ASC")->get();
        $vesselName = ForeignVessel::with('registration_info')
            ->orderBy("name","ASC")
            ->orderBy("arrival_date","desc")
            ->get();

//        $jettyList = AreaInfo::where("status", "=", "Y")->orderBy("name", "ASC")->get();
        $jettyList = JettyList::with([])->where('status', '=', 'A')->get();
        $pilotList = CpaPilot::where("status", "=", "A")->orderBy("name", "ASC")->get();
        $workingType = LVesselWorkingType::where("status", "=", "A")->orderBy("name", "ASC")->get();
        $pilotageType = LPilotageType::where("status", "=", "A")->orderBy("id", "ASC")->get();
        $scheduleType = LPilotageScheduleType::where("status", "=", "A")->orderBy("name", "ASC")->get();
        $workLocation = LPilotageWorkLocation::where("status", "=", "A")->orderBy("name", "ASC")->get();
        $vesselConditions = LVesselCondition::where("status", "=", "A")->get();
        $tugLists = TugsRegistration::where("status", "A")->get();

        return view("mda.certificate_entry", [
            "data" => $data,
            "vesselName" => $vesselName,
            "pilotList" => $pilotList,
            "workingType" => $workingType,
            "pilotageType" => $pilotageType,
            "scheduleType" => $scheduleType,
            "workLocation" => $workLocation,
            "vesselConditions" => $vesselConditions,
            "tugLists" => $tugLists,
            "jettyList" => $jettyList
        ]);
    }

    public function pilotageStore(Request $request)
    {
        $request->validate([
            'pilotage_type_id' => 'required',
//                'pilotage_from_time' => 'exclude_if:pilotage_type_id,""|exclude_if:pilotage_type_id,2|required',
//                'pilotage_to_time' => 'exclude_if:pilotage_type_id,""|exclude_if:pilotage_type_id,2|after_or_equal:pilotage_from_time|required',
            'shifted_from' => 'exclude_unless:pilotage_type_id,2|required',
            'shifted_to' => 'exclude_unless:pilotage_type_id,2|required',
            'vessel_id' => 'required',
//            'working_type_id' => 'required',
            'mother_vessel_id' => 'exclude_unless:working_type_id,1|required',
            'pilot_borded_at' => 'required',
            'pilot_left_at' => 'after_or_equal:pilot_borded_at|required',

//                'file_no' => 'required',
            'schedule_type_id' => 'required',
            'pilot_id' => 'required',

            'local_agent' => 'required',
            'last_port' => 'required',
//                'next_port' => 'required',
            'master_name' => 'required',
            'arrival_date' => 'required',
            'grt' => 'required',
//                'nrt' => 'required',
//            'work_location_id' => 'required',
            'mooring_from_time' => 'required',

            'mooring_to_time' => 'required',
            //'mooring_line_ford' => 'required',
            //'mooring_line_aft' => 'required',
            //'stern_power_avail' => 'required',
            'master_sign_date' => 'required',

            'master_sign' => 'mimes:pdf,jpg,jpeg,doc,docx|max:102400',
            'assistant_sign' => 'mimes:pdf,jpg,jpeg,doc,docx|max:102400',
            'certificate_form' => 'mimes:pdf,jpg,jpeg,doc,docx|max:102400',

            'tug.*.tugId' => 'required',
            'tug.*.assistanceFrom' => 'required',
            'tug.*.assistanceTo' => 'required|after_or_equal:tug.*.assistanceFrom',
            'tug.*.isPrimary' => 'required',
//            'tug.*.workLocation' => 'required'

//                ,'remarks' => 'required'
        ]);

//        dd($request);

        $response = $this->pilotageManager->pilotageCud("I", $request);

        $pilotagemsg = '';
        if ($response["status"] == 1) {
            $resPilotage = Pilotage::find($response['pilotage_id']);

            if ($resPilotage['pilotage_type_id'] == 1) {
                $pilotageRegUpdate = $this->pilotageManager->pilotageRegInfoUpdate($response['pilotage_id']);
                $pilotagemsg = $pilotageRegUpdate['status_message'];
            }
        }

        if ($response["status"]) {
            $message = $pilotagemsg ? redirect()->back()->with("success", $response["status_message"].' AND '.$pilotagemsg) : redirect()->back()->with("success", $response["status_message"]);
        } else {
            $message = redirect()->back()->with("error", $response["status_message"])->withInput();
        }

        return $message;
    }

    public function pilotageEdit($id)
    {
        $data = Pilotage::where("id", "=", $id)->with("pilotage_vessel_condition", "pilotage_tug", "mother_vessel", "pilotage_files")->first();

        //dd($data);

        $vesselName = ForeignVessel::orderBy("name", "ASC")->get();
//        $jettyList = AreaInfo::where("status", "=", "Y")->orderBy("name", "ASC")->get();
        $jettyList = JettyList::with([])->where('status', '=', 'A')->get();
        $pilotList = CpaPilot::where("status", "=", "A")->orderBy("name", "ASC")->get();
        $workingType = LVesselWorkingType::where("status", "=", "A")->orderBy("name", "ASC")->get();
        $pilotageType = LPilotageType::where("status", "=", "A")->orderBy("name", "ASC")->get();
        $scheduleType = LPilotageScheduleType::where("status", "=", "A")->orderBy("name", "ASC")->get();
        $workLocation = LPilotageWorkLocation::where("status", "=", "A")->orderBy("name", "ASC")->get();
        $vesselConditions = LVesselCondition::where("status", "=", "A")->get();
        $tugLists = TugsRegistration::where("status", "A")->get();

        return view("mda.certificate_entry", [
            "data" => $data,
            "vesselName" => $vesselName,
            "pilotList" => $pilotList,
            "workingType" => $workingType,
            "pilotageType" => $pilotageType,
            "scheduleType" => $scheduleType,
            "workLocation" => $workLocation,
            "vesselConditions" => $vesselConditions,
            "tugLists" => $tugLists,
            "jettyList" => $jettyList
        ]);
    }

    public function pilotageUpdate(Request $request, $id)
    {
        //dd($request);
        if ($request->isMethod("PUT")) {
            $request->validate([
                'pilotage_type_id' => 'required',
//                'pilotage_from_time' => 'exclude_if:pilotage_type_id,""|exclude_if:pilotage_type_id,2|required',
//                'pilotage_to_time' => 'exclude_if:pilotage_type_id,""|exclude_if:pilotage_type_id,2|after_or_equal:pilotage_from_time|required',
                'shifted_from' => 'exclude_unless:pilotage_type_id,2|required',
                'shifted_to' => 'exclude_unless:pilotage_type_id,2|required',
                'vessel_id' => 'required',
//                'working_type_id' => 'required',
                'mother_vessel_id' => 'exclude_unless:working_type_id,1|required',
                'pilot_borded_at' => 'required',
                'pilot_left_at' => 'after_or_equal:pilot_borded_at|required',

//                'file_no' => 'required',
                'schedule_type_id' => 'required',
                'pilot_id' => 'required',

                'local_agent' => 'required',
                'last_port' => 'required',
//                'next_port' => 'required',
                'master_name' => 'required',
                'arrival_date' => 'required',
                'grt' => 'required',
//                'nrt' => 'required',
//                'work_location_id' => 'required',
                'mooring_from_time' => 'required',

                'mooring_to_time' => 'required',
//                'mooring_line_ford' => 'required',
//                'mooring_line_aft' => 'required',
//                'stern_power_avail' => 'required',
                'master_sign_date' => 'required',

                /*'tug[0][tugId]' => 'required',
                'tug[0][assistanceFrom]' => 'required',
                'tug[0][assistanceTo]' => 'required',
                'tug[0][isPrimary]' => 'required',
                'tug[0][workLocation]' => 'required',*/

                'master_sign' => 'mimes:pdf,jpg,jpeg,doc,docx|max:102400',
                'assistant_sign' => 'mimes:pdf,jpg,jpeg,doc,docx|max:102400',
                'certificate_form' => 'mimes:pdf,jpg,jpeg,doc,docx|max:102400',
//                'remarks' => 'required'
            ]);

            $response = $this->pilotageManager->pilotageCud("U", $request, $id);

            if ($response["status"]) {
                $message = redirect()->back()->with("success", $response["status_message"]);
            } else {
                $message = redirect()->back()->with("error", $response["status_message"])->withInput();
            }

            return $message;
        }
    }

    public function pilotageDestroy(Request $request, $id)
    {
        if ($id) {
            $response = $this->pilotageManager->pilotageCud("D", $request, $id);

            $res = [
                "success" => ($response["status"]) ? True : false,
                "message" => $response["status_message"]
            ];
        } else {
            $res = [
                "success" => false,
                "message" => "Invalid require data"
            ];
        }

        return $res;
    }

    public function pilotageDatatable($id)
    {

        $response = $this->pilotageManager->pilotageDatatable();
        $datatable = $response ? $response : '';
//dd($datatable);
        return DataTables::of($datatable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use ($id) {
                if ($data['status'] == "P") {
                    $optionHtml = '<a href="' . route('ps-certificate-entry-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                    if ($id != $data['id']) {
                        $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('ps-certificate-entry-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                    }
                } else {
                    $optionHtml = "";
                }
                $optionHtml .= '<form name="report-generator" id="report-generator" method="post" target="_blank" action="' . route('report', ['title' => 'pilotage_certificate']) . '">
                            ' . csrf_field() . '
                            <input type="hidden" name="xdo" value="/~weblogic/MDA/RPT_ARRIVAL_REPORT_OF_VESSEL_AND_PILOTAGE_CERTIFICATE.xdo"/>
                            <input type="hidden" name="type" id="type" value="pdf" />
                            <input type="hidden" name="p_id" id="p_id" value="' . $data['id'] . '" />
                            <button type="submit" class="btn btn-sm cursor-pointer" data-toggle="tooltip" data-placement="top" title="Click to print report"><i class="bx bx-printer"></i></button>
                        </form>';
                //$optionHtml .= ' <div class="col-md-3"><a href="' . route('ps-verify-certificate-view', $data['id']) . '" class="" data-toggle="tooltip" data-placement="top" title="View certificate"><span  class="cursor-pointer" ><i class="bx bx-show"></i></span></a></div>';

                return $optionHtml;
            })
            ->editColumn('pilotage_type', function ($data) {
                return (isset($data["pilotage_type"])) ? $data["pilotage_type"]["name"] : "";
            })
            ->editColumn("vessel_name", function ($data) {
                return (isset($data["foreign_vessel"]) ? $data["foreign_vessel"]["name"] . "(" . $data["foreign_vessel"]["reg_no"] . ")" : '');
            })
            ->editColumn('cpa_pilot', function ($data) {
                return (isset($data["cpa_pilot"])) ? $data["cpa_pilot"]["name"] : "";
            })
            ->editColumn("pilot_borded_at", function ($data) {
                return HelperClass::defaultDateTimeFormat($data["pilot_borded_at"], "DATE");
            })
            ->editColumn("pilot_left_at", function ($data) {
                return HelperClass::defaultDateTimeFormat($data["pilot_left_at"], "DATE");
            })
            ->editColumn('status', function ($data) {
                switch ($data['status']) {
                    case 'A':
                        return "Requesting";
                        break;
                    case 'R':
                        return "Rejected";
                        break;
                    case 'C':
                        return "Approved";
                        break;
                    default:
                        return "Draft";
                        break;
                }
            })
            ->make(true);
    }

    public function downloadAfile($id)
    {
        $file = MediaFile::where("id", "=", $id)->get();

        $content = base64_decode($file[0]->files);
        return response()->make($content, 200, [
            'Content-Type' => $file[0]->file_type,
            'Content-Disposition' => 'attachment;filename="document.' . $file[0]->file_type . '"'
        ]);
    }

    public function foreignVesselsList($id)
    {
        $excludingSelectedVessel = ForeignVessel::where("status", "=", "A")->where("id", "!=", $id)->orderBy("name", "ASC")->get();
        //return $excludingSelectedVessel;
        return response()->json(["status" => "200", "vesselsList" => $excludingSelectedVessel]);
    }

    public function foreignVesselsDetails($id)
    {
        $noc_reg_info = DB::table('cpa_agent_portal.noc_request')->where('registration_no', $id)->first();
        $vesselsDetails = ForeignVessel::where("id", "=", $id)->get();

        $count_reg_no = 0;
        $new_reg_no = '';
        if(isset($noc_reg_info->circular_no)) {
            $count_reg_no = RegistrationInfo::where('registration_no', $id)->count();
            $new_reg_no = $noc_reg_info->circular_no;
        }

        if ($count_reg_no > 0) {
            if($count_reg_no == 1) {
                $new_reg_no = RegistrationInfo::where('registration_no', $id)->where('new_reg_no', $noc_reg_info->circular_no)->exists() ? $noc_reg_info->circular_no.'-'.$count_reg_no : $noc_reg_info->circular_no;
            } else {
                $new_reg_no = $noc_reg_info->circular_no.'-'.$count_reg_no;
            }
        }

        $is_channel = RegistrationInfo::where('registration_no', $id)->where('new_reg_no', $noc_reg_info->circular_no)->exists();
//        dd($vesselsDetails);
        return response()->json(["status" => "200", "vesselDetail" => $vesselsDetails, "noc_reg_info" => $noc_reg_info, 'new_reg_no' => $new_reg_no, 'is_channel' => $is_channel]);
    }

    public function tugsList()
    {
        $tugs = TugsRegistration::where("status", "A")->get();
        return response()->json(["status" => "200", "tugs" => $tugs]);
    }


    public function certificateList()
    {
        return view("mda.ps_verify_certificate");
    }

    public function certificateApprove(Request $request)
    {
//        dd($request);
        $pilotageId = $request->get("pilotage_id");

        if ($pilotageId) {
            $response = $this->pilotageManager->pilotageChangeStatus("U", $request, $pilotageId);

            if ($response["status_code"] == 1) {

                $jetty_tran = '';
                $location_not_allowed = [22120616000028951, 22102512009018007, 35, 36, 37, 38, 40, 45, 22101814009017773]; //BB, WBT, all RM
                $pilotage_data = Pilotage::find($pilotageId);

                if(($pilotage_data->pilotage_type_id == 3 && $pilotage_data->pilotage_to_loc) || ($pilotage_data->pilotage_type_id == 2 && $pilotage_data->shifted_to) )//for outward/shifting and if jetty id exists
                {
//                    dd($pilotage_data);
                    if(!in_array($pilotage_data->pilotage_from_loc, $location_not_allowed) &&  !in_array($pilotage_data->pilotage_to_loc, $location_not_allowed)) {

                        $jetty_tran = $this->jetty_transaction_cud('I', $pilotage_data, $pilotageId);
//                        $message = redirect()->back()->with("success", "PILOTAGE ENTRY SUCCESSFUL!" . " AND " . $jetty_tran["o_status_message"]);
                    }
                }
                /*$pilotage = $this->pilotageManager->pilotageDetails($pilotageId);

               //dd($pilotage);

              $invoice = [
                   'PILOTAGE' => [
                       [
                           'LABEL'=> 'PILOTAGE TYPE',
                           'VALUE'=> $pilotage[0]->pilotage_type->name
                       ],
                       [
                           'LABEL' => 'PILOTAGE FROM TIME',
                           'VALUE' => HelperClass::defaultDateTimeFormat($pilotage[0]->pilotage_from_time,"DATETIME")
                       ],
                       [
                           'LABEL' => 'PILOTAGE TO TIME',
                           'VALUE' => HelperClass::defaultDateTimeFormat($pilotage[0]->pilotage_to_time,"DATETIME")
                       ],
                       [
                           'LABEL' => 'SHIFTED FROM TIME',
                           'VALUE' => HelperClass::defaultDateTimeFormat($pilotage[0]->shifted_from,"DATETIME")
                       ],
                       [
                           'LABEL' => 'SHIFTED TO TIME',
                           'VALUE' => HelperClass::defaultDateTimeFormat($pilotage[0]->shifted_to,"DATETIME")
                       ],
                       [
                           'LABEL'=> 'VESSEL NAME',
                           'VALUE'=> $pilotage[0]->foreign_vessel->name
                       ],
                       [
                           'LABEL'=> 'WORK TYPE',
                           'VALUE'=> $pilotage[0]->working_type->name
                       ],
                       [
                           'LABEL'=> 'MOTHER VESSEL',
                           'VALUE'=> isset($pilotage[0]->mother_vessel->name) ? $pilotage[0]->mother_vessel->name : ""
                       ],
                       [
                           'LABEL'=> 'SCHEDULE TYPE',
                           'VALUE'=> $pilotage[0]->schedule_type->name
                       ],
                       [
                           'LABEL'=> 'PILOT NAME',
                           'VALUE'=> $pilotage[0]->cpa_pilot->name
                       ],
                       [
                           'LABEL'=> 'WORKING LOCATION',
                           'VALUE'=> $pilotage[0]->work_location->name
                       ]
                   ],
                   'TUGS' => null

               ];

               foreach ($pilotage[0]->pilotage_tug as $pTug){
                   $invoice['TUGS'][] = [
                       [
                           'LABEL'=>'TUG',
                           'VALUE'=>$pTug->tugs->name
                       ],
                       [
                           'LABEL'=>'Assistance from time',
                           'VALUE'=>HelperClass::defaultDateTimeFormat($pTug->assitance_from_time,"DATETIME")
                       ],
                       [
                           'LABEL'=>'Assistance to time',
                           'VALUE'=>HelperClass::defaultDateTimeFormat($pTug->assitance_to_time,"DATETIME")
                       ],
                       [
                           'LABEL'=>'Primary',
                           'VALUE'=>$pTug->primary_yn
                       ],
                       [
                           'LABEL'=>'Work Location',
                           'VALUE'=>$pTug->work_location->name
                       ]
                   ];
               }

               $fees = $this->pilotageManager->getPilotageFee($pilotage[0]);

               $grandTugFee = 0;

               foreach ($pilotage[0]->pilotage_tug as $pTug){
                  $response = $this->pilotageManager->getTugFee($pTug->primary_yn, $pTug->work_location_id, $pilotage[0]->grt, $pTug->assitance_from_time, $pTug->assitance_to_time);
                  $totalTugFee = $response["O_TOTAL_FEE"];
                  $tugFee = $response["O_TUG_FEE"];
                  $grandTugFee += ($totalTugFee+$tugFee);
               }

               $invoice["FEES"] = [
                   [
                       'LABEL' => 'PILOTAGE FEE',
                       'VALUE' => $fees["O_PILOTAGE_FEE"]
                   ],
                   [
                       'LABEL' => 'SHIFTING FEE',
                       'VALUE' => $fees["O_SHIFTING_FEE"]
                   ],
                   [
                       'LABEL' => 'TOTAL PILOTAGE NIGHT NAVIGATION FEE',
                       'VALUE' => $fees["O_PILOTAGE_NIGHT_NAV_FEE"]
                   ],
                   [
                       'LABEL' => 'TOTAL GUPTA KHAL FEE',
                       'VALUE' => $fees["O_GUPTA_KHAL_FEE"]
                   ],
                   [
                       'LABEL' => 'TOTAL PILOTAGE FEE',
                       'VALUE' => $fees["O_TOTAL_PILOTAGE_FEE"]+$grandTugFee
                   ],
                   [
                       'LABEL' => 'TOTAL SHIFTING FEE',
                       'VALUE' => $fees["O_TOTAL_SHIFTING_FEE"]
                   ],
                   [
                       'LABEL' => 'TOTAL TUG FEE',
                       'VALUE' => (double)$grandTugFee
                   ],
               ];


//                $response = $this->pilotageManager->pilotageInvoiceStore($pilotageId,"PILOTAGE", json_encode($invoice),"PILOTAGES");
                */
                $response = $this->pilotageManager->pilotageInvoiceStore($pilotageId, "PILOTAGE", json_encode([]), "PILOTAGES");
//dom

//                $pilotagemsg = '';

//                if ($response["status"] == 1) {
//                    $resPilotage = Pilotage::find($pilotageId);
//
//                    if ($resPilotage['pilotage_type_id'] == 1) {
//                        $pilotageRegUpdate = $this->pilotageManager->pilotageRegInfoUpdate($pilotageId);
//                        $pilotagemsg = $pilotageRegUpdate['status_message'];
//                    }
//                }

                $res = [
                    "success" => ($response["status"]) ? True : false,
//                    "message" => $pilotagemsg ? $response["status_message"] . ' And ' . $pilotagemsg : $response["status_message"],
                    "message" => $jetty_tran ? $response["status_message"]. ' AND ' . $jetty_tran["o_status_message"] : $response["status_message"],
                ];
            } else {
                $res = [
                    "success" => false,
                    "message" => $response["status_message"],
                ];
            }
        } else {
            $res = [
                "success" => false,
                "message" => "Invalid require data"
            ];
        }

        return $res;
    }

    public function certificateDetail($id)
    {
        $data = $this->pilotageManager->pilotageDetails($id);
//        dd($data);
        return view("mda.ps_verify_certificate_detail", ["data" => $data[0]]);
    }

    public function certificateDatatable(Request $request, $id)
    {
        $response = $this->pilotageManager->verifyCertificateDatatable($request);
        $datatable = $response ? $response : '';

        return DataTables::of($datatable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use ($id) {
                $optionHtml = '<div class="row">';
//                $optionHtml .=  '<div class="col-md-1"><a href="' . route('ps-verify-certificate-view', $data['id']) . '" class=""><i class="bx bx-show cursor-pointer"></i></a></div>';
                if ($id != $data['id']) {
                    if ($data["status"] == "C") {
                        $optionHtml .= '<div class="col-md-3"><a href="' . route('ps-verify-certificate-view', $data['id']) . '" class="" data-toggle="tooltip" data-placement="top" title="View certificate"><span  class="cursor-pointer" ><i class="bx bx-show"></i></span></a></div>';
                        $optionHtml .= '<div class="col-md-3"><span  data-pilotageid="' . $data['id'] . '" id="approveCertificate' . $data['id'] . '" data-toggle="tooltip" data-placement="top" title="Certificate approved"><i class="bx bx-check-double"></i></span></div>';
                    } elseif ($data["status"] == "A") {
                        if ($data['workflow_process'] != null) {
                            $optionHtml .= '<div class="col-md-3"><a href="' . route('ps-verify-certificate-view', $data['id']) . '" class=""><span><i class="bx bx-show" data-toggle="tooltip" data-placement="top" title="View certificate"></i></span></a></div>';
                            $optionHtml .= '<div class="col-md-3"><a data-pilotageid="' . $data['id'] . '" href="javascript:void(0)" class="show-receive-modal approveBtn" title="Approve"><i class="bx bx-check-circle cursor-pointer"></i></a></div>';
                            //$optionHtml .= '<div class="col-md-3"><span  data-pilotageid="'.$data['id'].'" data-status="C" onclick="approve_disapprove(this)" id="approveCertificate'.$data['id'].'" class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Approve certificate" ><i class="bx bx-check-circle" aria-hidden="true"></i></span></div>';
                            $optionHtml .= '<div class="col-md-3 text-danger"><span  data-pilotageid="' . $data['id'] . '" data-status="R" onclick="approve_disapprove(this)" id="rejectCertificate' . $data['id'] . '" class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Reject certificate"><i class="bx bx-trash"></i></span></div>';
                        } else {
                            $optionHtml .= '<div class="col-md-3"><a href="' . route('ps-verify-certificate-view', $data['id']) . '" class=""><span><i class="bx bx-show" data-toggle="tooltip" data-placement="top" title="View certificate"></i></span></a></div>';
                            $optionHtml .= '<div class="col-md-3"><a data-pilotageid="' . $data['id'] . '" href="javascript:void(0)" class="show-receive-modal workflowBtn" title="Assign Workflow"><i class="bx bx-sitemap cursor-pointer"></i></a></div>';
                            //$optionHtml .= '<div class="col-md-3"><span  data-pilotageid="'.$data['id'].'" data-status="C" onclick="approve_disapprove(this)" id="approveCertificate'.$data['id'].'" class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Approve certificate" ><i class="bx bx-check-circle" aria-hidden="true"></i></span></div>';
                            $optionHtml .= '<div class="col-md-3 text-danger"><span  data-pilotageid="' . $data['id'] . '" data-status="R" onclick="approve_disapprove(this)" id="rejectCertificate' . $data['id'] . '" class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Reject certificate"><i class="bx bx-trash"></i></span></div>';
                        }
                        /*$optionHtml .= '<div class="col-md-3"><a href="'. route('ps-verify-certificate-view', $data['id']) . '" class=""><span><i class="bx bx-show" data-toggle="tooltip" data-placement="top" title="View certificate"></i></span></a></div>';
                        $optionHtml .= '<div class="col-md-3"><span  data-pilotageid="'.$data['id'].'" data-status="C" onclick="approve_disapprove(this)" id="approveCertificate'.$data['id'].'" class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Approve certificate" ><i class="bx bx-check-circle" aria-hidden="true"></i></span></div>';
                        $optionHtml .= '<div class="col-md-3 text-danger"><span  data-pilotageid="'.$data['id'].'" data-status="R" onclick="approve_disapprove(this)" id="rejectCertificate'.$data['id'].'" class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Reject certificate"><i class="bx bx-trash"></i></span></div>';*/
                    }
                    $optionHtml .= '</div>';
                }
                return $optionHtml;
            })
            ->editColumn('pilotage_type', function ($data) {
                return (isset($data["pilotage_type"]["name"])) ? $data["pilotage_type"]["name"] : "";
            })
            ->editColumn("vessel_name", function ($data) {
                return (isset($data["foreign_vessel"]) ? $data["foreign_vessel"]["name"] . "(" . $data["foreign_vessel"]["reg_no"] . ")" : '');
            })
            ->editColumn('cpa_pilot', function ($data) {
                return (isset($data["cpa_pilot"]["name"])) ? $data["cpa_pilot"]["name"] : "";
            })
            ->editColumn("pilot_borded_at", function ($data) {
                return HelperClass::defaultDateTimeFormat($data["pilot_borded_at"], "DATE");
            })
            ->editColumn("pilot_left_at", function ($data) {
                return HelperClass::defaultDateTimeFormat($data["pilot_left_at"], "DATE");
            })
            ->editColumn('status', function ($data) {
                return isset($data['status']) && $data['status'] == "A" ? 'Approved' : 'Pending';
            })->editColumn('invoice_no', function ($data) {
                return $data['invoice'];
            })
            ->make(true);
    }

    public function getMasterName(Request $request)
    {
        $pilotage = Pilotage::where('VESSEL_ID', $request->vessel)->orderBy('created_at', 'desc')->first();
        $reg_info = RegistrationInfo::where('REGISTRATION_NO', $request->vessel)->orderBy('update_date', 'desc')->first(['new_reg_no', 'deck_cargo']);
        return [
            'vessel_reg_no' => isset($reg_info) ? $reg_info->new_reg_no : '',
            'deck_cargo' => isset($reg_info) ? $reg_info->deck_cargo: '',
            'master_name' => isset($pilotage) ? $pilotage->master_name : '',
            'work_location_id' => isset($pilotage) ? $pilotage->work_location_id : '',
            'fixed_mooring' => isset($pilotage) ? $pilotage->fixed_mooring : '',
            'swing_mooring' => isset($pilotage) ? $pilotage->swing_mooring : '',
            'draught' => isset($pilotage) ? $pilotage->draught : '',
            'length' => isset($pilotage) ? $pilotage->length_value : '',
            'call_sign' => isset($pilotage) ? $pilotage->call_sign : '',
            'crw_officer_incl_mst_num' => isset($pilotage) ? $pilotage->crw_officer_incl_mst_num : '',
            'owner_address' => isset($pilotage) ? $pilotage->owner_address : '',
        ];
    }

    public function jetty_transaction_cud($action, $request, $pilotage_id, $transaction_id=null)
    {
        $agency_id = '';

        if($action == 'I') {
            $registration_no = $request->vessel_id;
            $new_reg_no = $request->new_reg_no;
            $query = <<<QUERY
SELECT VESSEL_NAME,IMO_NO,RI.EX_RATE , TO_CHAR(ARIVAL_DATE,'DD/MM/YYYY') ARIVAL_DATE , TO_CHAR(RI.REGISTRATION_DATE,'DD/MM/YYYY') REGISTRATION_DATE, RI.grt,RI.AGENCY_ID, RI.NEW_REG_NO
FROM VSL.VSL_REGISTRATION_INFO RI
LEFT JOIN VSL.VSL_MASTER_INFO MI ON RI.REGISTRATION_NO = MI.REGISTRATION_NO
WHERE RI.REGISTRATION_NO = :registration_no
AND RI.NEW_REG_NO = :new_reg_no
QUERY;

            $data = DB::selectOne($query, ['registration_no' => $registration_no, 'new_reg_no' => $new_reg_no]);

            $pilotage_date = Pilotage::where('id', $pilotage_id)->pluck('pilot_borded_at')->first();
//        $berthing_data = Pilotage::where('pilotage_type_id', 1)->where('vessel_reg_no', $registration_no)->where('status', '!=', 'D')->first();
            $berthing_data = Pilotage::where('vessel_reg_no', $registration_no)
                ->where('new_reg_no', $new_reg_no)
                ->where('status', '!=', 'D')
                ->where('pilot_borded_at', '<=', $pilotage_date)
                ->orderBy('pilot_borded_at', 'desc')
                ->skip(1)
                ->first(); //takes 2nd last data -- previous date

            if ($berthing_data->pilotage_type_id == 1) {
                $mooring_from_time = $berthing_data->mooring_from_time;
            } else {
                $mooring_from_time = $berthing_data->mooring_to_time;
            }

            if ($berthing_data->pilotage_to_loc == 22112114003021961 || $berthing_data->pilotage_to_loc == 41 || $berthing_data->pilotage_to_loc == 22101913006017800) //for DDJ, DDJ-1 and DDJ-2
            {
                $agency_id = $this->pilotageManager->getDryDockId()->agency_id;
            } else {
                if ($transaction_id) {
                    $agency_id = '';
                } else {
                    $agency_id = $request->agent_id;
                }
            }
        }

        if(!$agency_id) {
            $agency_id = RegistrationInfo::where('registration_no', $request->vessel_id)
                ->where('new_reg_no', $request->new_reg_no)
                ->pluck('agency_id')
                ->first();
        }

        $procedure_name = 'VSL.JETTY_TRANSACTION_CUD';

        DB::beginTransaction();
        try {
            $jetty_status_code = sprintf("%4000s", "");
            $jetty_status_message = sprintf("%4000s", "");

            $params = [
                'P_ACTION_TYPE' => $action,
                'P_TRANSACTION_ID' => [
                    'value' => &$transaction_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'P_REGISTRATION_NO' => $transaction_id ? '' : $registration_no,
                'P_NEW_REG_NO' => isset($request->new_reg_no) ? $request->new_reg_no : '',
                'P_REGISTRATION_DATE' => $transaction_id ? '' : date('Y/m/d', strtotime($data->registration_date)),
                'P_ARIVAL_DATE' => $transaction_id ? '' : date('Y/m/d', strtotime($request->arrival_date)),
                'P_ARIVAL_TIME' => '',
                'P_BERTHING_DATE' => $transaction_id ? '' : date('d/m/Y H:i', strtotime($mooring_from_time)),
                'P_DEPAR_DATE' => $transaction_id ? '' : date('d/m/Y H:i', strtotime($request->mooring_to_time)),
                'P_NO_OF_OCCATION' => '',
                'P_JETTY_CRANE_USED' => '',
                'P_JETTY_CRANE_USED_SHIFT' => '',
                'P_JETTY_CRANE_USED_NOT' => '',
                'P_JETTY_CRANE_USED_NOT_SHIFT' => '',
                'P_JETTY_CRANE_CAN1' => '',
                'P_JETTY_CRANE_CAN2' => '',
                'P_M_CRANE_USED' => '',
                'P_DERRICK_USED' => '',
                'P_QTY_L_2' => '',
                'P_QTY_L_2_H' => '',
                'P_QTY_L_4' => '',
                'P_QTY_L_4_H' => '',
                'P_QTY_E_2' => '',
                'P_QTY_E_2_H' => '',
                'P_QTY_E_4' => '',
                'P_QTY_E_4_H' => '',
                'P_QTY_L_2_EX' => '',
                'P_QTY_L_2_H_EX' => '',
                'P_QTY_L_4_EX' => '',
                'P_QTY_L_4_H_EX' => '',
                'P_QTY_E_2_EX' => '',
                'P_QTY_E_2_H_EX' => '',
                'P_QTY_E_4_EX' => '',
                'P_QTY_E_4_H_EX' => '',
                'P_REMARKS' => '',
                'P_STATUS' => '',
                'P_INSERT_BY' => auth()->id(),
                'P_UPDATE_BY' => auth()->id(),
                'P_BILL_TYPE_ID' => BillTypeEnum::JETTY_BILL,
                'P_AGENCY_ID' => $agency_id,
                'P_APPROVED_YN' => $transaction_id ? '' : YesNoFlag::YES,
                'P_APPROVED_BY' => auth()->user()->emp_id,
                'P_APPROVED_DATE' => '',
                'P_JETTY_ID' => ($berthing_data->pilotage_type_id == 2) ? $berthing_data->shifted_to : $berthing_data->pilotage_to_loc,
                'P_WATER_BARGE' => '',
                'P_WATER_MAIN' => '',
                'P_WATER_MAIN_CANCEL' => '',
                'P_IS_IMPORT' => '',
                'P_EX_RATE' => $transaction_id ? '' : $this->getExchangeRate($registration_no),
                'P_REF_PILOTAGE_ID' => $pilotage_id,
                'o_status_code' => &$jetty_status_code,
                'o_status_message' => &$jetty_status_message,
            ];

            DB::executeProcedure($procedure_name, $params);

            if($params['o_status_code'] == 1)
            {
                DB::commit();
            }
            else
            {
                DB::rollBack();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => 'Exception: ' . $e->getMessage()];
        }

        return $params;
    }

    public function getExchangeRate($reg_no)
    {
        if($reg_no)
        {
            $arrival_date = RegistrationInfo::where('registration_no', $reg_no)->pluck('arival_date')->first();
        }
        else
        {
            return 1;
        }

        $arrival_date = date('Y-m-d', strtotime($arrival_date));

        $exc_rate = '';
        if($arrival_date) {
//            $exc_rate = LExchangeRate::where('rate_date', $arrival_date)->pluck('exc_rate')->first();

            try {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $e_rate = sprintf("%4000s", "");

                $params = [
                    'P_RATE_DATE' => $arrival_date,
                    'P_BILL_GENERATE' => 'N',
                    'O_EXC_RATE' => &$e_rate,
                    'o_status_code' => &$status_code,
                    'o_status_message' => &$status_message,
                ];

                \DB::executeProcedure('VSL.GET_EXCHANGE_RATE', $params);

                $exc_rate = $params['O_EXC_RATE'];
            } catch (\Exception $e) {
                return ["exception" => true, "o_status_code" => 99, "o_status_message" => 'Exception: ' . $e->getMessage()];
            }
        }
        return $exc_rate;
    }
}
