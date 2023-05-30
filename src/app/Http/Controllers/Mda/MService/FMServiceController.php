<?php

namespace App\Http\Controllers\Mda\MService;

use App\Entities\Mda\CpaVessel;
use App\Entities\Mda\LocalVessel;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class FMServiceController extends Controller
{
    public function index()
    {
        return view('mda.mservice.fm.index');
    }

    public function datatable()
    {
        $queryResult = DB::table('MDA.VSL_F_M_SERVICE')->orderBy('insert_date', 'DESC')->get();

        return datatables()->of($queryResult)
            ->addColumn('alongside_date', function ($query) {
                if ($query->alongside_date_time == null) {
                    return '--';
                }
                return Carbon::parse($query->alongside_date_time)->format('d-m-Y');
            })
            ->addColumn('alongside_time', function ($query) {
                if ($query->alongside_date_time == null) {
                    return '--';
                }
                return date('H:i', strtotime($query->alongside_date_time));
            })
            ->addColumn('sail_date', function ($query) {
                if ($query->sail_date_time == null) {
                    return '--';
                }
                return Carbon::parse($query->sail_date_time)->format('d-m-Y');
            })
            ->addColumn('sail_time', function ($query) {
                if ($query->sail_date_time == null) {
                    return '--';
                }
                return date('H:i', strtotime($query->sail_date_time));
            })
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('fixed-mooring-service-edit', [$query->fm_ser_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $data = DB::table('MDA.VSL_F_M_SERVICE')->where('fm_ser_id', '=', $id)->first();
        $docData = DB::table('MDA.MEDIA_FILES')->where('ref_id', '=', $id)->get();

        return view('mda.mservice.fm.index', [
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

        return redirect()->route('fixed-mooring-service');
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

        return redirect()->route('fixed-mooring-service');
    }

    private function ins_upd(Request $request)
    {   //dd($request->all());
        $postData = $request->post();
        if (isset($postData['fm_ser_id'])) {
            $fm_ser_id = $postData['fm_ser_id'];
        } else {
            $fm_ser_id = '';
        }
        $alongside_date = isset($postData['alongside_date']) ? date('Y-m-d', strtotime($postData['alongside_date'])) : '';
        $sail_date = isset($postData['sail_date']) ? date('Y-m-d', strtotime($postData['sail_date'])) : '';

        $alongside_time = isset($postData['alongside_time']) ? date('H:i:s', strtotime(str_replace(' ', '', $postData['alongside_time']))) : '';
        $sail_time = isset($postData['sail_time']) ? date('H:i:s', strtotime(str_replace(' ', '', $postData['sail_time']))) : '';

        $alongside_date_time = $alongside_date . ' ' . $alongside_time;
        $sail_date_time = $sail_date . ' ' . $sail_time;

        $vName = DB::table('MDA.LOCAL_VESSELS')->where('id',$request->get('vessel_id'))->get(['name', 'agency_id'])->first();
        $agent_id = isset($vName->agency_id) ? $vName->agency_id : '';

        if(isset($vName)){
            $vName = $vName->name;
        }else{
            $vName = null;
        }

        $jName = DB::table('VSL.VSL_JETTY_LIST')->where('jetty_id',$request->get('jetty_id'))->get(['jetty_name'])->first();
        if(isset($jName)){
            $jName = $jName->jetty_name;
        }else{
            $jName = null;
        }

        $aName = DB::table('VSL.SECDBMS_L_AGENCY')->where('agency_id',$agent_id)->get(['agency_name'])->first();

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
                'P_FM_SER_ID' => [
                    'value' => &$fm_ser_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'P_SERIAL_NO' => $postData['ser_serial_no'],
                'P_VESSEL_ID' => $postData['vessel_id'],
                'P_VESSEL_NAME' => $vName,
                'P_VESSEL_OWNER_NAME' => $aName,
                'P_VESSEL_OWNER_ADDRESS' => isset($postData['vessel_owner_address']) ? $postData['vessel_owner_address'] : '',
                'P_JETTY_ID' => $postData['jetty_id'],
                'P_JETTY_NAME' => $jName,
                'P_ALONGSIDE_DATE_TIME' => $alongside_date_time,
                'P_SAIL_DATE_TIME' => $sail_date_time,
                'P_TOTAL_USED_TIME' => $postData['total_used_time'] ? $postData['total_used_time'] : '',
                'P_AGENT_ID' => $agent_id,
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('MDA.VSL_SERVICE_PROCE.VSL_F_M_SERVICE_CUD', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            //if (empty($request->get("doc_id"))) {
            if ($request->get("doc_type")) {
                $ref_id = $params['P_FM_SER_ID']['value'];
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
                        "P_SOURCE_TABLE" => 'VSL_F_M_SERVICE',
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

    public function getLocalVessel(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = DB::table('MDA.LOCAL_VESSELS')->where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(name)'), 'like', strtolower('%' . trim($searchTerm) . '%'));
        })->orderBy('name', 'ASC')->limit(10)->get(['id', 'name']);

        return $empId;
    }

    /*public function getCPAVesselInfo(Request $request)
    {
        $searchTerm = $request->get('vessel_id');
        $data = DB::table('MDA.LOCAL_VESSELS')->where('id', $searchTerm)->get(['OWNER_NAME', 'OWNER_ADDRESS'])->first();
        return $data->owner_name . '+' . $data->owner_address;
    }*/
    public function getCPAVesselInfo(Request $request)
    {
        $searchTerm = $request->get('ship_agent');
        $data = DB::table('VSL.SECDBMS_L_AGENCY')->where('agency_id', $searchTerm)->get(['address'])->first();
        return $data->address;
    }
}
