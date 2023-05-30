<?php

namespace App\Http\Controllers\Mda\MService;

use App\Entities\Mda\CpaVessel;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class TugCancelServiceController extends Controller
{
    public function index()
    {
        return view('mda.mservice.tc.index', [
            'gen_uniq_id' => DB::selectOne('select MDA.YMD_SEQUENCE  as unique_id from dual')->unique_id,
        ]);
    }

    public function datatable()
    {
        $queryResult = DB::table('MDA.VSL_T_C_SERVICE')->orderBy('insert_date', 'DESC')->get();

        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('tug-cancel-service-edit', [$query->tc_ser_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $data = DB::table('MDA.VSL_T_C_SERVICE')->where('tc_ser_id', '=', $id)->first();
        $docData = DB::table('MDA.MEDIA_FILES')->where('ref_id', '=', $id)->get();

        return view('mda.mservice.tc.index', [
            'data' => $data,
            'docData' => $docData,
        ]);
    }

    public function store(Request $request)
    {
        $response = $this->ins_upd($request);
        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('tug-cancel-service');
    }

    public function update(Request $request, $id)
    {
        $response = $this->ins_upd($request, $id);

        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('tug-cancel-service');
    }

    private function ins_upd(Request $request)
    {   //dd($request->all());
        $postData = $request->post();
        if (isset($postData['tc_ser_id'])) {
            $tc_ser_id = $postData['tc_ser_id'];
        } else {
            $tc_ser_id = '';
        }

        $vName = DB::table('MDA.FOREIGN_VESSELS')->where('v_r_id',$request->get('vessel_id'))->get(['name'])->first();
        if(isset($vName)){
            $vName = $vName->name;
        }else{
            $vName = null;
        }
        $aName = DB::table('VSL.SECDBMS_L_AGENCY')->where('agency_id',$request->get('agent_id'))->get(['agency_name'])->first();
        if(isset($aName)){
            $aName = $aName->agency_name;
        }else{
            $aName = null;
        }
        $pName = DB::table('MDA.CPA_PILOTS')->where('id',$request->get('pilot_id'))->get(['name'])->first();
        if(isset($pName)){
            $pName = $pName->name;
        }else{
            $pName = null;
        }

        $pilot_borded_at = $request->get('pilot_borded_at');
        $pilot_borded_at = isset($pilot_borded_at) ? date('Y-m-d H:i:s', strtotime($pilot_borded_at)) : '';

        $pilot_left_at = $request->get('pilot_left_at');
        $pilot_left_at = isset($pilot_left_at) ? date('Y-m-d H:i:s', strtotime($pilot_left_at)) : '';

        $canFrmName = DB::table('VSL.VSL_JETTY_LIST')->where('jetty_id',$request->get('canceled_from_id'))->get(['jetty_name'])->first();
        if(isset($canFrmName)){
            $canFrmName = $canFrmName->jetty_name;
        }else{
            $canFrmName = null;
        }
        $canToName = DB::table('VSL.VSL_JETTY_LIST')->where('jetty_id',$request->get('canceled_to_id'))->get(['jetty_name'])->first();
        if(isset($canFrmName)){
            $canToName = $canToName->jetty_name;
        }else{
            $canToName = null;
        }

        $canceled_at = $request->get('canceled_at');
        $canceled_at = isset($canceled_at) ? date('Y-m-d H:i:s', strtotime($canceled_at)) : '';

        $date_of_last_visit = $request->get('date_of_last_visit');
        $date_of_last_visit = isset($date_of_last_visit) ? date('Y-m-d', strtotime($date_of_last_visit)) : '';

        $tName = DB::table('MDA.TUGS')->where('id',$request->get('tug_id'))->get(['name'])->first();
        if(isset($tName)){
            $tName = $tName->name;
        }else{
            $tName = null;
        }

        $today = date('Y-m-d');

        $port_auth_from_hrs = $request->get('port_auth_from_hrs');
        $port_auth_from_hrs = isset($port_auth_from_hrs) ? date('H:i:s', strtotime(str_replace(' ', '', $port_auth_from_hrs))) : '';
        $port_auth_from_hrs = $today . ' ' . $port_auth_from_hrs;

        $port_auth_to_hrs = $request->get('port_auth_to_hrs');
        $port_auth_to_hrs = isset($port_auth_to_hrs) ? date('H:i:s', strtotime(str_replace(' ', '', $port_auth_to_hrs))) : '';
        $port_auth_to_hrs = $today . ' ' . $port_auth_to_hrs;

        $launches_from_hrs = $request->get('launches_from_hrs');
        $launches_from_hrs = isset($launches_from_hrs) ? date('H:i:s', strtotime(str_replace(' ', '', $launches_from_hrs))) : '';
        $launches_from_hrs = $today . ' ' . $launches_from_hrs;

        $launches_to_hrs = $request->get('launches_to_hrs');
        $launches_to_hrs = isset($launches_to_hrs) ? date('H:i:s', strtotime(str_replace(' ', '', $launches_to_hrs))) : '';
        $launches_to_hrs = $today . ' ' . $launches_to_hrs;

        $hawser_boats_from_hrs = $request->get('hawser_boats_from_hrs');
        $hawser_boats_from_hrs = isset($hawser_boats_from_hrs) ? date('H:i:s', strtotime(str_replace(' ', '', $hawser_boats_from_hrs))) : '';
        $hawser_boats_from_hrs = $today . ' ' . $hawser_boats_from_hrs;

        $hawser_boats_to_hrs = $request->get('hawser_boats_to_hrs');
        $hawser_boats_to_hrs = isset($hawser_boats_to_hrs) ? date('H:i:s', strtotime(str_replace(' ', '', $hawser_boats_to_hrs))) : '';
        $hawser_boats_to_hrs = $today . ' ' . $hawser_boats_to_hrs;

        $mooring_gangs_from_hrs = $request->get('mooring_gangs_from_hrs');
        $mooring_gangs_from_hrs = isset($mooring_gangs_from_hrs) ? date('H:i:s', strtotime(str_replace(' ', '', $mooring_gangs_from_hrs))) : '';
        $mooring_gangs_from_hrs = $today . ' ' . $mooring_gangs_from_hrs;

        $mooring_gangs_to_hrs = $request->get('mooring_gangs_to_hrs');
        $mooring_gangs_to_hrs = isset($mooring_gangs_to_hrs) ? date('H:i:s', strtotime(str_replace(' ', '', $mooring_gangs_to_hrs))) : '';
        $mooring_gangs_to_hrs = $today . ' ' . $mooring_gangs_to_hrs;


        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'P_TC_SER_ID' => [
                    'value' => &$tc_ser_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'P_SERIAL_NO' => $request->get('ser_serial_no'),
                'P_VESSEL_ID' => $request->get('vessel_id'),
                'P_VESSEL_NAME' => $vName,
                'P_CALL_SIGN' => $request->get('call_sign'),
                'P_FLAG_ID' => $request->get('flag_id'),
                'P_FLAG' => $request->get('flag'),
                'P_VESSEL_MASTER' => $request->get('vessel_master'),
                'P_GRT' => $request->get('grt'),
                'P_NRT' => $request->get('nrt'),
                'P_MAX_FRESH_WATER_DRFT' => $request->get('max_fresh_water_drft'),
                'P_DECK_CARGO' => $request->get('deck_cargo'),
                'P_AGENT_ID' => $request->get('agent_id'),
                'P_AGENT_NAME' => $aName,
                'P_PILOT_ID' => $request->get('pilot_id'),
                'P_PILOT_NAME' => $pName,
                'P_BOARDED_AT' => $pilot_borded_at,
                'P_LEFT_AT' => $pilot_left_at,
                'P_CANCELED_FROM_ID' => $request->get('canceled_from_id'),
                'P_CANCELED_FROM_NAME' => $canFrmName,
                'P_CANCELED_TO_ID' => $request->get('canceled_to_id'),
                'P_CANCELED_TO_NAME' => $canToName,
                'P_CANCELED_AT' => $canceled_at,
                'P_WHETHER_APP_PORT' => $request->get('whether_app_port'),
                'P_WHETHER_MOVE_CANCEL' => $request->get('whether_move_cancel'),
                'P_DATE_OF_LAST_VISIT' => $date_of_last_visit,
                'P_PORT_AUTH_TUG_ID' => $request->get('tug_id'),
                'P_PORT_AUTH_TUG_NAME' => $tName,
                'P_PORT_AUTH_FROM_HRS' => $port_auth_from_hrs,
                'P_PORT_AUTH_TO_HRS' => $port_auth_to_hrs,
                'P_LAUNCHES_NAME' => $request->get('launches_name'),
                'P_LAUNCHES_FROM_HRS' => $launches_from_hrs,
                'P_LAUNCHES_TO_HRS' => $launches_to_hrs,
                'P_HAWSER_BOATS_NAME' => $request->get('hawser_boats_name'),
                'P_HAWSER_BOATS_FROM_HRS' => $hawser_boats_from_hrs,
                'P_HAWSER_BOATS_TO_HRS' => $hawser_boats_to_hrs,
                'P_MOORING_GANGS_NAME' => $request->get('mooring_gangs_name'),
                'P_MOORING_GANGS_FROM_HRS' => $mooring_gangs_from_hrs,
                'P_MOORING_GANGS_TO_HRS' => $mooring_gangs_to_hrs,
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('MDA.VSL_SERVICE_PROCE.VSL_T_C_SERVICE_CUD', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            //if (empty($request->get("doc_id"))) {
            if ($request->get("doc_type")) {
                $ref_id = $params['P_TC_SER_ID']['value'];
                foreach ($request->get("doc_type") as $indx => $value) {
                    $id = null;
                    $data = $request->get("doc")[$indx];
                    $doc_content = substr($data, strpos($data, ",") + 1);
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params_doc = [
                        "P_ACTION_TYPE" => 'I',
                        "P_ID" => [
                            'value' => &$id,
                            'type' => \PDO::PARAM_INPUT_OUTPUT,
                            'length' => 255
                        ],
                        "P_FILES" => ['value' => $doc_content, 'type' => PDO::PARAM_LOB],
                        "P_STATUS" => 'A',
                        "P_CREATED_BY" => auth()->id(),
                        "P_UPDATED_BY" => '',
                        "P_SOURCE_TABLE" => 'VSL_T_C_SERVICE',
                        "P_REF_ID" => $ref_id,
                        "P_TITLE" => $request->get("doc_name")[$indx],
                        "P_FILE_TYPE" => $request->get("doc_type")[$indx],
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];//dd($params);
                    DB::executeProcedure("MDA.MDA_CORE_PROCE.MEDIA_FILES_CUD", $params_doc);
                }
            }
            //}

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            //dd($params,$e->getMessage(), 'ok2');
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }

    public function getPilotList(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = DB::table('MDA.CPA_PILOTS')->where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(name)'), 'like', strtolower('%' . trim($searchTerm) . '%'));
        })->orderBy('name', 'ASC')->limit(10)->get(['id','name']);

        return $empId;
    }
}
