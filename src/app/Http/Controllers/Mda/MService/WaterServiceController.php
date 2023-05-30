<?php

namespace App\Http\Controllers\Mda\MService;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class WaterServiceController extends Controller
{
    public function index()
    {
        return view('mda.mservice.water.index');
    }

    public function datatable()
    {
        $queryResult = DB::table('MDA.VSL_WATER_SERVICE')->orderBy('insert_date', 'DESC')->get();

        return datatables()->of($queryResult)
            ->addColumn('supply_date', function ($query) {
                if ($query->supply_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->supply_date)->format('d-m-Y');
                }
            })
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('water-service-edit', [$query->w_ser_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $data = DB::table('MDA.VSL_WATER_SERVICE')->where('w_ser_id', '=', $id)->first();
        $docData = DB::table('MDA.MEDIA_FILES')->where('ref_id', '=', $id)->get();

        return view('mda.mservice.water.index', [
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

        return redirect()->route('water-service');
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

        return redirect()->route('water-service');
    }

    private function ins_upd(Request $request)
    {   //dd($request->all());
        $postData = $request->post();
        if (isset($postData['w_ser_id'])) {
            $w_ser_id = $postData['w_ser_id'];
        } else {
            $w_ser_id = '';
        }
        $supply_date = isset($postData['supply_date']) ? date('Y-m-d', strtotime($postData['supply_date'])) : '';

        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'P_W_SER_ID' => [
                    'value' => &$w_ser_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'P_RECEIPT_NO' => $postData['receipt_no'],
                'P_VESSEL_ID' => $postData['vessel_id'],
                'P_AGENT_ID' => $postData['agent_id'],
                'P_JETTY_ID' => $postData['jetty_id'],
                'P_SUPPLY_DATE' => $supply_date,
                'P_RECEIVED_QTY' => $postData['received_qty'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];//dd($params);
            DB::executeProcedure('MDA.VSL_SERVICE_PROCE.VSL_WATER_SERVICE_CUD', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }
            if ($request->get("doc_type")) {
                $ref_id = $params['P_W_SER_ID']['value'];
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
                        "P_SOURCE_TABLE" => 'VSL_WATER_SERVICE',
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
}
