<?php

namespace App\Http\Controllers\Mda\MService;

use App\Entities\Mda\CpaVessel;
use App\Entities\Mda\MediaFile;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class NavyServiceController extends Controller
{
    public function index()
    {
        return view('mda.mservice.navy.index');
    }

    public function datatable()
    {
        $queryResult = DB::table('MDA.VSL_NAVY_SERVICE')->orderBy('insert_date', 'DESC')->get();

        return datatables()->of($queryResult)
            ->addColumn('working_date', function ($query) {
                if ($query->working_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->working_date)->format('d-m-Y');
                }
            })
            ->addColumn('from_time', function ($query) {
                if ($query->from_time == null) {
                    return '--';
                }
                return date('H:i', strtotime($query->from_time));
            })
            ->addColumn('to_time', function ($query) {
                if ($query->to_time == null) {
                    return '--';
                }
                return date('H:i', strtotime($query->to_time));
            })
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('navy-service-edit', [$query->n_ser_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $data = DB::table('MDA.VSL_NAVY_SERVICE')->where('n_ser_id', '=', $id)->first();
        $docData = DB::table('MDA.MEDIA_FILES')->where('ref_id', '=', $id)->get();

        return view('mda.mservice.navy.index', [
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

        return redirect()->route('navy-service');
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

        return redirect()->route('navy-service');
    }

    private function ins_upd(Request $request)
    {   //dd($request->all());
        $postData = $request->post();
        if(isset($postData['n_ser_id'])){
            $n_ser_id = $postData['n_ser_id'];
        }else{
            $n_ser_id = '';
        }
        $working_date = isset($postData['working_date']) ? date('Y-m-d', strtotime($postData['working_date'])) : '';

        $startTime = isset($postData['from_time']) ? date('H:i:s', strtotime(str_replace(' ', '', $postData['from_time']))) : '';
        $endTime = isset($postData['to_time']) ? date('H:i:s', strtotime(str_replace(' ', '', $postData['to_time']))) : '';

        $pStartTime = $working_date . ' ' . $startTime;
        $pEndTime = $working_date . ' ' . $endTime;

        $vName = DB::table('MDA.CPA_VESSELS')->where('id',$request->get('vessel_id'))->get(['name'])->first();
        if(isset($vName)){
            $vName = $vName->name;
        }else{
            $vName = null;
        }

        $lfName = DB::table('VSL.VSL_JETTY_LIST')->where('jetty_id',$request->get('jetty_id_from'))->get(['jetty_name'])->first();
        if(isset($lfName)){
            $lfName = $lfName->jetty_name;
        }else{
            $lfName = null;
        }

        $ltName = DB::table('VSL.VSL_JETTY_LIST')->where('jetty_id',$request->get('jetty_id_to'))->get(['jetty_name'])->first();
        if(isset($ltName)){
            $ltName = $ltName->jetty_name;
        }else{
            $ltName = null;
        }

        $aName = DB::table('VSL.SECDBMS_L_AGENCY')->where('agency_id',$request->get('ship_agent'))->get(['agency_name'])->first();
        if(isset($aName)){
            $aName = $aName->agency_name;
        }else{
            $aName = null;
        }

        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'P_N_SER_ID' => [
                    'value' => &$n_ser_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'P_SER_SERIAL_NO' => $request->get('ser_serial_no'),
                'P_SER_SERIAL_NO_2' => $request->get('ser_serial_no_2'),
                'P_VESSEL_ID' => $request->get('vessel_id'),
                'P_VESSEL_NAME' => $vName,
                'P_WORKING_DATE' => $working_date,
                'P_LOC_FROM_ID' => $request->get('jetty_id_from'),
                'P_LOC_FROM_NAME' => $lfName,
                'P_LOC_TO_ID' => $request->get('jetty_id_to'),
                'P_LOC_TO_NAME' => $ltName,
                'P_FROM_TIME' => $pStartTime,
                'P_TO_TIME' => $pEndTime,
                'P_TOTAL_TIME' => $request->get('total_time'),
                'P_WORK_DESC' => $postData['work_desc'],
                'P_DETAILS' => $postData['details'],
                'P_AGENT_ID' => $postData['ship_agent'],
                'P_AGENT_NAME' => $aName,
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('MDA.VSL_SERVICE_PROCE.VSL_NAVY_SERVICE_CUD', $params);//dd($params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }
                if ($request->get("doc_type")) {
                    $ref_id = $params['P_N_SER_ID']['value'];
                    foreach ($request->get("doc_type") as $indx => $value) {
                        $id = null;
                        $data = $request->get("doc")[$indx];
                        $doc_content = substr($data, strpos($data, ",") + 1);
                        $status_code = sprintf("%4000s", "");
                        $status_message = sprintf("%4000s", "");
                        $file_params = [
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
                            "P_SOURCE_TABLE" => 'VSL_NAVY_SERVICE',
                            "P_REF_ID" => $ref_id,
                            "P_TITLE" => $request->get("doc_name")[$indx],
                            "P_FILE_TYPE" => $request->get("doc_type")[$indx],
                            "o_status_code" => &$status_code,
                            "o_status_message" => &$status_message
                        ];
                        DB::executeProcedure("MDA.MDA_CORE_PROCE.MEDIA_FILES_CUD", $file_params);
                    }
                }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }

    public function getCPAVessel(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = DB::table('MDA.VTMIS_VESSEL_REGISTRATION')->where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(vessel_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'));
        })->where("status_code", "!=", "D")->where("vessel_flag", "1")->orderBy('vessel_name', 'ASC')->limit(10)->get(['id','vessel_name']);

        return $empId;
    }

    public function getJettyList(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = DB::table('VSL.VSL_JETTY_LIST')->where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(jetty_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'));
        })->where("status", "!=", "D")->orderBy('jetty_name', 'ASC')->limit(10)->get(['jetty_id','jetty_name']);

        return $empId;
    }

    public function getVesselNavy(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = DB::table('MDA.CPA_VESSELS')->where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(name)'), 'like', strtolower('%' . trim($searchTerm) . '%'));
        })->where("status", "!=", "D")->orderBy('name', 'ASC')->limit(10)->get(['id','name']);

        return $empId;
    }

    public function downloader($id)
    {
        $docData = MediaFile::find($id);

        if ($docData) {
            if ($docData->files && $docData->title && $docData->file_type) {
                $content = base64_decode($docData->files);

                return response()->make($content, 200, [
                    'Content-Type' => $docData->doc_type,
                    'Content-Disposition' => 'attachment; filename="' . $docData->title .'-'.$docData->ref_id.'-'.$docData->id.'.'.$docData->file_type. '"'
                ]);
            }
        }
    }

    public function removeDoc(Request $request)
    {
        if ($request->get('doc_id')) {
            DB::beginTransaction();
            $getReturn = MediaFile::where('id', $request->get('doc_id'))->delete();
        }
        if ($getReturn == '1') {
            $result = 'success';
            DB::commit();
        } else {
            $result = 'failure';
            DB::rollback();
        }
        return $result;
    }
}
