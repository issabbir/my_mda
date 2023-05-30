<?php

namespace App\Http\Controllers\Mda\MService;

use App\Entities\Mda\CpaVessel;
use App\Entities\Mwe\WorkType;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class TugServiceController extends Controller
{
    public function index()
    {
        return view('mda.mservice.tug.index', [
            'gen_uniq_id' => DB::selectOne('select MDA.YMD_SEQUENCE  as unique_id from dual')->unique_id,
            'workTypes' => WorkType::all(),
        ]);
    }

    public function datatable()
    {
        $queryResult = DB::table('MDA.VSL_TUG_SERVICE')->orderBy('insert_date', 'DESC')->get();

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
                $actionBtn = '<a title="Edit" href="' . route('tug-service-edit', [$query->t_ser_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $data = DB::table('MDA.VSL_TUG_SERVICE')->where('t_ser_id', '=', $id)->first();
        $docData = DB::table('MDA.MEDIA_FILES')->where('ref_id', '=', $id)->get();

        return view('mda.mservice.tug.index', [
            'data' => $data,
            'docData' => $docData,
            'workTypes' => WorkType::all(),
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

        return redirect()->route('tug-service');
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

        return redirect()->route('tug-service');
    }

    private function ins_upd(Request $request)
    {   //dd($request->all());
        $postData = $request->post();
        if(isset($postData['t_ser_id'])){
            $t_ser_id = $postData['t_ser_id'];
        }else{
            $t_ser_id = '';
        }
        $working_date = isset($postData['working_date']) ? date('Y-m-d', strtotime($postData['working_date'])) : '';

        $startTime = isset($postData['from_time']) ? date('H:i:s', strtotime(str_replace(' ', '', $postData['from_time']))) : '';
        $endTime = isset($postData['to_time']) ? date('H:i:s', strtotime(str_replace(' ', '', $postData['to_time']))) : '';

        $pStartTime = $working_date . ' ' . $startTime;
        $pEndTime = $working_date . ' ' . $endTime;

        $vName = DB::table('MDA.FOREIGN_VESSELS')->where('v_r_id',$postData['vessel_id'])->get(['name'])->first();
        if(isset($vName)){
            $vName = $vName->name;
        }else{
            $vName = null;
        }

        $tName = DB::table('MDA.TUGS')->where('id',$postData['tug_id'])->get(['name'])->first();
        if(isset($tName)){
            $tName = $tName->name;
        }else{
            $tName = null;
        }

        $jfName = DB::table('VSL.VSL_JETTY_LIST')->where('jetty_id',$request->get('jetty_id_from'))->get(['jetty_name'])->first();
        if(isset($jfName)){
            $jfName = $jfName->jetty_name;
        }else{
            $jfName = null;
        }

        $jtName = DB::table('VSL.VSL_JETTY_LIST')->where('jetty_id',$request->get('jetty_id_to'))->get(['jetty_name'])->first();
        if(isset($jtName)){
            $jtName = $jtName->jetty_name;
        }else{
            $jtName = null;
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
                'P_T_SER_ID' => [
                    'value' => &$t_ser_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'P_SER_SERIAL_NO' => $postData['ser_serial_no'],
                'P_VESSEL_ID' => $postData['vessel_id'],
                'P_VESSEL_NAME' => $vName,
                'P_TUG_ID' => $postData['tug_id'],
                'P_TUG_NAME' => $tName,
                'P_WORKING_DATE' => $working_date,
                'P_LOC_FROM_ID' => $postData['jetty_id_from'],
                'P_LOC_FROM_NAME' => $jfName,
                'P_LOC_TO_ID' => $postData['jetty_id_to'],
                'P_LOC_TO_NAME' => $jtName,
                'P_FROM_TIME' => $pStartTime,
                'P_TO_TIME' => $pEndTime,
                'P_TOTAL_TIME' => $postData['total_time'],
                'P_WORK_DESC' => $postData['work_desc'],
                'P_DETAILS' => $postData['details'],
                'P_AGENT_ID' => $postData['ship_agent'],
                'P_AGENT_NAME' => $aName,
                'P_WORK_ID' => $postData['work_id'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            try {
                DB::executeProcedure('MDA.VSL_SERVICE_PROCE.VSL_TUG_SERVICE_CUD', $params);
            }catch (\Exception $e){
                dd($e);
            }


            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            //if (empty($request->get("doc_id"))) {
                if ($request->get("doc_type")) {
                    $ref_id = $params['P_T_SER_ID']['value'];
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
                            "P_SOURCE_TABLE" => 'VSL_TUG_SERVICE',
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

    public function getForeignVessel(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = DB::table('MDA.FOREIGN_VESSELS')->where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(name)'), 'like', strtolower('%' . trim($searchTerm) . '%'));
        })->where("status", "!=", "D")->where("status", "!=", null)->orderBy('name', 'ASC')->limit(10)->get(['id','name','arrival_date','v_r_id']);
        return $empId;
    }

    public function getTugVessel(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = DB::table('MDA.VSL_TUG_SERVICE')->where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(vessel_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'));
        })->orderBy('vessel_name', 'ASC')->limit(10)->get(['vessel_id','vessel_name'])->unique('vessel_name');
        return $empId;
    }

    public function getTugList(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = DB::table('MDA.TUGS')->where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(name)'), 'like', strtolower('%' . trim($searchTerm) . '%'));
        })->where("status", "!=", "D")->orderBy('name', 'ASC')->limit(10)->get(['id','name']);

        return $empId;
    }
}
