<?php

namespace App\Http\Controllers\Mda\MService;

use App\Entities\Mda\CpaVessel;
use App\Entities\Mda\VslPortScrapService;
use App\Entities\Mwe\WorkType;
use App\Entities\VSL\RegistrationInfo;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class PortScrapServiceController extends Controller
{
    public function index()
    {
        $data = new VslPortScrapService();

        $vessels = DB::select('SELECT VR.ID,
       VR.REG_NO,
       VR.VESSEL_NAME,
       NR.CIRCULAR_NO
  FROM VTMIS.VESSEL_REGISTRATION VR, CPA_AGENT_PORTAL.NOC_REQUEST NR
 WHERE VR.REG_NO = NR.REGISTRATION_NO');

        $remarks = DB::select('SELECT * FROM MDA.L_REMARKS');

        return view('mda.mservice.port_scrap.index', [
            'data' => $data,
            'vessels' => $vessels,
            'remarks' => $remarks,
        ]);
    }

    public function datatable()
    {
        $queryResult = DB::table('MDA.VSL_PORT_SCRAP_SERVICE')->orderBy('insert_date', 'DESC')->get();

        return datatables()->of($queryResult)
            ->addColumn('arrival_date', function ($query) {
                if ($query->arrival_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->arrival_date)->format('d-m-Y H:i');
                }
            })
            ->addColumn('departure_date', function ($query) {
                if ($query->departure_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->departure_date)->format('d-m-Y H:i');
                }
            })
            ->addColumn('remarks', function ($query) {
                if ($query->remarks_id) {
                    return DB::table('MDA.L_REMARKS')
                            ->where('remarks_id', $query->remarks_id)
                            ->pluck('remark_name')
                            ->first();
                }
            })
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('port-scrap-service-edit', [$query->ps_ser_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $data = DB::table('MDA.VSL_PORT_SCRAP_SERVICE')->where('ps_ser_id', '=', $id)->first();
        $docData = DB::table('MDA.MEDIA_FILES')->where('ref_id', '=', $id)->get();

        $vessels = DB::select('SELECT VR.ID,
       VR.REG_NO,
       VR.VESSEL_NAME,
       NR.CIRCULAR_NO
  FROM VTMIS.VESSEL_REGISTRATION VR, CPA_AGENT_PORTAL.NOC_REQUEST NR
 WHERE VR.REG_NO = NR.REGISTRATION_NO');

        $remarks = DB::select('SELECT * FROM MDA.L_REMARKS');

        return view('mda.mservice.port_scrap.index', [
            'data' => $data,
            'docData' => $docData,
            'vessels' => $vessels,
            'remarks' => $remarks,
        ]);
    }

    public function store(Request $request)
    {
//        dd($request);
        $response = $this->ins_upd($request);
        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('port-scrap-service');
    }

    public function update(Request $request, $id)
    {
//        dd($request);
        $response = $this->ins_upd($request, $id);

        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('port-scrap-service');
    }

    private function ins_upd(Request $request, $id = null)
    {   //dd($request->all());
        $postData = $request->post();

        if(isset($postData['ps_ser_id'])){
            $ps_ser_id = $postData['ps_ser_id'];
        }else{
            $ps_ser_id = '';
        }

        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'P_PS_SER_ID' => [
                    'value' => &$ps_ser_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'P_REG_NO' => $postData['vessel'],
                'P_VESSEL_NAME' => $postData['vessel_name'],
                'P_AGENT_ID' => $postData['agent_id'],
                'P_AGENT_NAME' => $postData['agent_name'],
                'P_ARRIVAL_DATE' => date('Y-m-d H:i:s', strtotime($postData['arrival_date'])),
                'P_DEPARTURE_DATE' => date('Y-m-d H:i:s', strtotime($postData['departure_date'])),
                'P_FLAG_ID' => $postData['flag_id'],
                'P_FLAG_NAME' => $postData['flag_name'],
                'P_GRT' => $postData['grt'],
                'P_NRT' => $postData['nrt'],
                'P_REMARKS_ID' => $postData['remarks'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
//            dd($params);
            try {
                DB::executeProcedure('MDA.VSL_SERVICE_PROCE.VSL_PORT_SCRAP_SERVICE_CUD', $params);

            }catch (\Exception $e){
//                dd($e);
                DB::rollBack();
                return $params;
            }


            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            //if (empty($request->get("doc_id"))) {
            if ($request->get("doc_type")) {
                $ref_id = $params['P_PS_SER_ID']['value'];
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
                        "P_SOURCE_TABLE" => 'VSL_PORT_SCRAP_SERVICE',
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

    public function getVesselInfo(Request $request)
    {
        return DB::select('SELECT VR.*,
       NR.CIRCULAR_NO, GC.COUNTRY
  FROM VTMIS.VESSEL_REGISTRATION VR, CPA_AGENT_PORTAL.NOC_REQUEST NR, PMIS.L_GEO_COUNTRY GC
 WHERE VR.REG_NO = NR.REGISTRATION_NO
 AND GC.COUNTRY_ID = VR.VESSEL_FLAG
 AND VR.REG_NO = :reg_no',['reg_no' => $request->vessel_reg_no]);
    }
}
