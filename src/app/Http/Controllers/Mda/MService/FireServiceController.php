<?php

namespace App\Http\Controllers\Mda\MService;

use App\Entities\Mda\CpaVessel;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class FireServiceController extends Controller
{
    public function index()
    {
        return view('mda.mservice.fire.index');
    }

    public function datatable()
    {
        $queryResult = DB::table('MDA.VSL_FIRE_SERVICE_MST')->orderBy('insert_date', 'DESC')->get();

        return datatables()->of($queryResult)
            ->addColumn('flag', function ($query) {
                if ($query->vessel_flag == 1) {
                    return 'BD';
                } else {
                    return 'FOREIGN';
                }
            })
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('fire-service-edit', [$query->f_ser_mst_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $data = DB::table('MDA.VSL_FIRE_SERVICE_MST')->where('f_ser_mst_id', '=', $id)->first();
        $dData = DB::table('MDA.VSL_FIRE_SERVICE_DTL')->where('f_ser_mst_id', '=', $id)->get();
        $docData = DB::table('MDA.MEDIA_FILES')->where('ref_id', '=', $id)->get();

        return view('mda.mservice.fire.index', [
            'data' => $data,
            'dData' => $dData,
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

        return redirect()->route('fire-service');
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

        return redirect()->route('fire-service');
    }

    private function ins_upd(Request $request)
    {
        //dd($request->all());
        $postData = $request->post();
        if (isset($postData['f_ser_mst_id'])) {
            $f_ser_mst_id = $postData['f_ser_mst_id'];
        } else {
            $f_ser_mst_id = '';
        }

        $flag = $postData['vessel_flag'];

        if($flag ==1){
            $vName = DB::table('MDA.VTMIS_VESSEL_REGISTRATION')->where('id',$postData['bd_vessel_id'])->get(['vessel_name'])->first();
            $vName = $vName->vessel_name;
        }else{
            $vName = DB::table('MDA.FOREIGN_VESSELS')->where('v_r_id',$postData['fr_vessel_id'])->get(['name'])->first();
            $vName = $vName->name;
        }

        if(!isset($vName)){
            $vName = null;
        }

        $aName = DB::table('VSL.SECDBMS_L_AGENCY')->where('agency_id',$request->get('ship_agent'))->get(['agency_name'])->first();
        if(isset($aName)){
            $aName = $aName->agency_name;
        }else{
            $aName = null;
        }

        $jettyName = DB::table('VSL.VSL_JETTY_LIST')->where('jetty_id',$request->get('jetty_id'))->get(['jetty_name'])->first();
        if(isset($jettyName)){
            $jettyName = $jettyName->jetty_name;
        }else{
            $jettyName = null;
        }

        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'P_F_SER_MST_ID' => [
                    'value' => &$f_ser_mst_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'P_SER_SERIAL_NO' => $postData['ser_serial_no'],
                'P_VESSEL_FLAG' => $flag,
                'P_BD_VESSEL_ID' => isset($postData['bd_vessel_id']) ? $postData['bd_vessel_id'] : '',
                'P_FR_VESSEL_ID' => isset($postData['fr_vessel_id']) ? $postData['fr_vessel_id'] : '',
                'P_VESSEL_NAME' => $vName,
                'P_S_AGENT_ID' => isset($postData['ship_agent']) ? $postData['ship_agent'] : '',
                'P_AGENCY_NAME' => $aName,
                'P_JETTY_ID' => isset($postData['jetty_id']) ? $postData['jetty_id'] : '',
                'P_JETTY_NAME' => $jettyName,
                'P_EQP_QUANTITY' => isset($postData['eqp_quantity']) ? $postData['eqp_quantity'] : '',
                'P_FIRE_FIGHTER_NO' => isset($postData['fire_fighter_no']) ? $postData['fire_fighter_no'] : '',
                'P_WORK_DESC' => $postData['work_desc'],
                'P_DETAILS' => $postData['details'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('MDA.VSL_SERVICE_PROCE.VSL_FIRE_SERVICE_MST_CUD', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            if (!empty($request->get("tab_date_dtl"))) {//dd($request->all());
                $ref_id = $params['P_F_SER_MST_ID']['value'];
                foreach ($request->get('tab_date_dtl') as $indx => $value) {
                    $tab_date_dtl = isset($request->get('tab_date_dtl')[$indx]) ? date('Y-m-d', strtotime($request->get('tab_date_dtl')[$indx])) : '';

                    $startTime = isset($request->get('tab_from_time')[$indx]) ? date('H:i:s', strtotime(str_replace(' ', '', $request->get('tab_from_time')[$indx]))) : '';
                    $endTime = isset($request->get('tab_to_time')[$indx]) ? date('H:i:s', strtotime(str_replace(' ', '', $request->get('tab_to_time')[$indx]))) : '';

                    $pStartTime = $tab_date_dtl . ' ' . $startTime;
                    $pEndTime = $tab_date_dtl . ' ' . $endTime;

                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params_dtl = [
                        'P_F_SER_DTL_ID' => null,
                        "P_F_SER_MST_ID" => $ref_id,
                        "P_DUTY_FROM_DATE_TIME" => $pStartTime,
                        "P_DUTY_TO_DATE_TIME" => $pEndTime,
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];//dd($params_dtl);

                    DB::executeProcedure("MDA.VSL_SERVICE_PROCE.VSL_FIRE_SERVICE_DTL_CUD", $params_dtl);
                    if ($params_dtl['o_status_code'] != 1) {
                        DB::rollBack();
                        return $params_dtl;
                    }
                }
            }

            if ($request->get("doc_type")) {
                $ref_id = $params['P_F_SER_MST_ID']['value'];
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
                        "P_SOURCE_TABLE" => 'VSL_FIRE_SERVICE',
                        "P_REF_ID" => $ref_id,
                        "P_TITLE" => $request->get("doc_name")[$indx],
                        "P_FILE_TYPE" => $request->get("doc_type")[$indx],
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];
                    DB::executeProcedure("MDA.MDA_CORE_PROCE.MEDIA_FILES_CUD", $params_doc);
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }

    public function getShippingAgent(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = DB::table('VSL.SECDBMS_L_AGENCY')->where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(agency_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'));
        })->orderBy('agency_name', 'ASC')->limit(10)->get(['agency_id', 'agency_name']);

        return $empId;
    }

    public function removeData(Request $request)
    {//dd($request->all());
        try {
            foreach ($request->get('f_ser_dtl_id') as $indx => $value) {
                DB::table('MDA.VSL_FIRE_SERVICE_DTL')
                    ->where('f_ser_dtl_id', $request->get("f_ser_dtl_id")[$indx])
                    ->delete();
            }
            return '1';
        } catch (\Exception $e) {
            DB::rollBack();
            return '0';
        }

    }
}
