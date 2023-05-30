<?php

namespace App\Managers\Mda;


use App\Contracts\Mda\SmContract;
use App\Entities\Mda\MooringVisit;
use App\Entities\Mda\SwingMooring;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;

class SmManager implements SmContract
{
    public function swingMooringsCud($action_type = null, $request = [], $id = 0)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID" => ["value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                "P_SERIAL_NO" => $request->get("serial_no"),
                "P_NAME" => $request->get("name"),
                "P_DETAILS" => $request->get("details"),
                "P_USER_ID" => auth()->id(),
                "P_STATUS" => $request->get("status"),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];

            DB::executeProcedure("MDA.MDA_CORE_PROCE.SWING_MOORINGS_CUD", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                "status_code" => $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function swingMooringsDatatable()
    {
        return SwingMooring::Where('status', '!=', 'D')->orderBy("created_at", 'desc')->get();
    }

    public function mooringVisitsCud($action_type = null, $request = [], $id = 0)
    {//dd($request->all());
        $aName = DB::table('VSL.SECDBMS_L_AGENCY')->where('agency_id', $request->get('ship_agent') ? $request->get('ship_agent') : $request->get('agent_id'))->get(['agency_name'])->first();
        if (isset($aName)) {
            $aName = $aName->agency_name;
        } else {
            $aName = null;
        }

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID" => ["value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                "P_CPA_VESSEL_ID" => $request->get("cpa_vessel"),
                "P_LOCAL_VESSEL_ID" => $request->get("local_vessel"),
                "P_SWING_MOORING_ID" => $request->get("swing_moorings"),
                "P_LM_REP" => $request->get("lm_rep"),
                "P_VISIT_DATE" => $request->get("visit_date"),
                "P_SL_NO" => $request->get("sl_no"),
                'P_AGENCY_ID' => $request->get("agent_id"),
                'P_AGENCY_NAME' => $aName,
                "P_USER_ID" => auth()->id(),
                "P_STATUS" => ($request->get("status") != "") ? $request->get("status") : "P",
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];

            DB::executeProcedure("MDA.MDA_CORE_PROCE.MOORING_VISITS_CUD", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                "status_code" => $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];

        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function mooringVisitsDatatable($request)
    {
        //$data =  MooringVisit::Where('status', '=', 'P')->orWhere('status', '=', 'C');
        $sql = MooringVisit::with("cpa_vessel", "local_vessel", "swing_moorings");
        if ($request->get('from_date') != "" && $request->get('to_date') != "") {
            //$fromDate = date('Y-m-d H:i:s', strtotime(empty($request->get('from_date'))?date("Y-m-d", strtotime('-7 days')):$request->get('from_date') ));
            $fromDate = date('Y-m-d H:i:s', strtotime($request->get('from_date')));
            $toDate = date('Y-m-d H:i:s', strtotime($request->get('to_date')));
            $sql->whereBetween('visit_date', [$fromDate, $toDate]);
        }

        return $sql->where('status', '!=', 'D')
            ->orderBy("visit_date", 'desc')
            ->get();
    }

    public function smInsApprovalDatatable($request)
    {
        $data = MooringVisit::orderBy("visit_date", 'desc');
        if ($request->get('from_date') != "" && $request->get('to_date') != "") {
            //$fromDate = date('Y-m-d H:i:s', strtotime(empty($request->get('from_date'))?date("Y-m-d", strtotime('-7 days')):$request->get('from_date') ));
            $fromDate = date('Y-m-d H:i:s', strtotime($request->get('from_date')));
            $toDate = date('Y-m-d H:i:s', strtotime($request->get('to_date')));
            $data->whereBetween('visit_date', [$fromDate, $toDate]);
        }

        $data->where('status', '!=', 'D')
            ->with("cpa_vessel")
            ->with("local_vessel")
            ->with("swing_moorings")
            ->get();

        return $data;
    }

    public function mooringVisitChangeStatus($action_type = null, $request = [], $id = null)
    {
        // TODO: Implement mooringVisitChangeStatus() method.
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID" => (int)$id,
                "P_INSPECTOR_ID" => (int)auth()->id(),
                "P_INSPECTOR_DATE" => date("y-m-d"),
                "P_UPDATED_BY" => (int)auth()->id(),
                "P_STATUS" => $request->get("status"),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message
            ];
            DB::executeProcedure("MDA.MDA_CORE_PROCE.MOORING_VISITS_VERIFY_CUD", $params);
            $response = ["status" => true, "status_code" => $params["O_STATUS_CODE"], "status_message" => $params["O_STATUS_MESSAGE"]];

        } catch (\Exception $ex) {
            $response = ["status" => false, "status_code" => 99, "status_message" => $ex->getMessage()];
        }

        return $response;
    }
}
