<?php

namespace App\Http\Controllers\Mda\MService;

use App\Entities\Mda\CpaVessel;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class ForkliftServiceController extends Controller
{
    public function index()
    {
        return view('mda.mservice.forklift.index', [
            'gen_uniq_id' => DB::selectOne('select MDA.YMD_SEQUENCE  as unique_id from dual')->unique_id,
        ]);
    }

    public function datatable()
    {
        $queryResult = DB::table('MDA.VSL_FORKLIFT_SERVICE_MST')->orderBy('insert_date', 'DESC')->get();

        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('forklift-service-edit', [$query->fl_ser_mst_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $data = DB::table('MDA.VSL_FORKLIFT_SERVICE_MST')->where('fl_ser_mst_id', '=', $id)->first();
        $dtldata = DB::table('MDA.VSL_FORKLIFT_SERVICE_DTL')->where('fl_ser_mst_id', '=', $id)->orderBy("fl_ser_dtl_id", 'asc')->get();
        $docData = DB::table('MDA.MEDIA_FILES')->where('ref_id', '=', $id)->orderBy("ref_id", 'asc')->get();

        return view('mda.mservice.forklift.index', [
            'data' => $data,
            'dtldata' => $dtldata,
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

        return redirect()->route('forklift-service');
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

        return redirect()->route('forklift-service');
    }

    private function ins_upd(Request $request)
    {   //dd($request->all());
        $postData = $request->post();
        if (isset($postData['fl_ser_mst_id'])) {
            $fl_ser_mst_id = $postData['fl_ser_mst_id'];
        } else {
            $fl_ser_mst_id = '';
        }
        $arrival_date = isset($postData['arrival_date']) ? date('Y-m-d', strtotime($postData['arrival_date'])) : '';

        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'P_FL_SER_MST_ID' => [
                    'value' => &$fl_ser_mst_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'P_SER_SERIAL_NO' => $postData['ser_serial_no'],
                'P_VESSEL_ID' => $postData['vessel_id'],
                'P_REGISTRATION_NO' => $postData['registration_no'],
                'P_ARRIVAL_DATE' => $arrival_date,
                'P_JETTY_ID' => $postData['jetty_id'],
                'P_COMPANY_NAME' => $postData['company_name'],
                'P_AGENT_ID' => $postData['agent_id'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('MDA.VSL_SERVICE_PROCE.VSL_FORKLIFT_SERVICE_MST_CUD', $params);//dd($params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            if (!empty($request->get("tab_typ_me"))) {//dd($request->all());
                $ref_id = $params['P_FL_SER_MST_ID']['value'];
                foreach ($request->get('tab_typ_me') as $indx => $value) {
                    $tab_date_dtl = isset($request->get('tab_date_dtl')[$indx]) ? date('Y-m-d', strtotime($request->get('tab_date_dtl')[$indx])) : '';

                    $startTime = isset($request->get('tab_from_time')[$indx]) ? date('H:i:s', strtotime(str_replace(' ', '', $request->get('tab_from_time')[$indx]))) : '';
                    $endTime = isset($request->get('tab_to_time')[$indx]) ? date('H:i:s', strtotime(str_replace(' ', '', $request->get('tab_to_time')[$indx]))) : '';

                    $pStartTime = $tab_date_dtl . ' ' . $startTime;
                    $pEndTime = $tab_date_dtl . ' ' . $endTime;

                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params_dtl = [
                        'P_FL_SER_DTL_ID' => null,
                        "P_FL_SER_MST_ID" => $ref_id,
                        "P_SERIAL_NO" => $request->get('tab_ser_no')[$indx],
                        "P_DTL_DATE" => $tab_date_dtl,
                        "P_TYP_OF_ME" => $request->get('tab_typ_me')[$indx],
                        "P_NO_OF_ME" => $request->get('tab_me_no')[$indx],
                        "P_IN_TIME" => $pStartTime,
                        "P_OUT_TIME" => $pEndTime,
                        "P_DTL_REMARKS" => $request->get('tab_remarks_dtl')[$indx],
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];//dd($params_dtl);

                    DB::executeProcedure("MDA.VSL_SERVICE_PROCE.VSL_FORKLIFT_SERVICE_DTL_CUD", $params_dtl);
                    if ($params_dtl['o_status_code'] != 1) {
                        DB::rollBack();
                        return $params_dtl;
                    }
                }
            }

            //if (empty($request->get("doc_id"))) {
                if ($request->get("doc_type")) {
                    $ref_id = $params['P_FL_SER_MST_ID']['value'];
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
                            "P_SOURCE_TABLE" => 'VSL_FORKLIFT_SERVICE_MST_CUD',
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
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }

    public function getVesselInfo(Request $request)
    {
        $searchTerm = $request->get('vessel_id');
//        $data = DB::select('SELECT fv.*, VR.new_reg_no FROM MDA.FOREIGN_VESSELS fv, VSL.VSL_REGISTRATION_INFO vr WHERE FV.ID = VR.REGISTRATION_NO AND FV.STATUS != \'D\' AND LOWER(fv.V_R_ID) LIKE ?', ['%' . $searchTerm . '%']);
        $data = DB::table('MDA.FOREIGN_VESSELS as fv')
            ->join('VSL.VSL_REGISTRATION_INFO as vr', 'fv.ID', '=', 'vr.REGISTRATION_NO')
            ->select('fv.*', 'vr.new_reg_no')
            ->where('fv.STATUS', '<>', 'D')
            ->where(DB::raw('LOWER(fv.V_R_ID)'), 'LIKE', '%' . strtolower($searchTerm) . '%')
            ->first();
//        $data = DB::table('MDA.FOREIGN_VESSELS')->where('V_R_ID', $searchTerm)->first();
        return  response(
            [
                'result' => $data,
            ]
        );
    }

    public function getForeignVessel(Request $request)
    {
        $searchTerm = $request->get('term');

        $empId = DB::select('SELECT fv.*, VR.new_reg_no FROM MDA.FOREIGN_VESSELS fv, VSL.VSL_REGISTRATION_INFO vr WHERE FV.ID = VR.REGISTRATION_NO AND FV.STATUS != \'D\' AND LOWER(fv.name) LIKE ?', ['%' . strtolower(trim($searchTerm)) . '%']);
        return $empId;
    }

    public function removeData(Request $request)
    {//dd($request->all());
        try {
            foreach ($request->get('fl_ser_dtl_id') as $indx => $value) {
                DB::table('MDA.VSL_FORKLIFT_SERVICE_DTL')
                    ->where('fl_ser_dtl_id', $request->get("fl_ser_dtl_id")[$indx])
                    ->delete();
            }
            return '1';
        } catch (\Exception $e) {
            DB::rollBack();
            return '0';
        }

    }
}
