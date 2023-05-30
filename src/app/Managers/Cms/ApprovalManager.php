<?php

namespace App\Managers\Cms;
use App\Contracts\Cms\ApprovalContract;
use App\Helpers\HelperClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ApprovalManager implements ApprovalContract
{

    public function authorized($request = [])
    {

        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "I_WORKFLOW_MAPPING_ID" => $request->get("workflow_mapping_id"),
                "I_REFERENCE_COMMENT" => $request->get("comment"),
                "I_REFERENCE_STATUS" => $request->get("auth_status"),
                "P_CREATED_BY" =>auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.CM_ENGINE.WORKFLOW_ROLEWISE_MAP_SAVE", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                "status_code" => $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
            if ($params['O_STATUS_CODE'] === '1' && $request->get('cur_workflow_role')==='CM_VESSEL_INSPECTOR' || $request->get('cur_workflow_role')==='SENIOR_HYDROGRAPHER_FIELD'){
                $this->fuelReceivingProcess($request);
            }

        } catch (\Exception $e) {
            DB::rollback();
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        DB::commit();
        return $response;
    }

    public function sendToApproval($request = [])
    {
        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_cpa_vessel_id" =>$request->get('cpa_vessel_id') ,
                "P_INSERT_BY" => auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message
            ];
            DB::executeProcedure("MDA.CM_ENGINE.WF_MAPPING_ENTRY", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                "status_code" => $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $response = ["status" => false, "status_code" => 99,  "status_message" => 'Approval data process error.'];
        }
        DB::commit();
        return $response;
    }

    public function fuelReceivingProcess($request=[]){
        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_FUEL_CONSUMPTION_MST_ID" =>$request->get('fuel_consumption_mst_id') ,
                "P_RECEIVED_DATE" =>HelperClass::convert_date_form_ddmmyyyy_to_oracle_date($request->get('received_date')),
                "P_RECEIVED_FUEL" =>$request->get('received_fuel') ,
                "P_INSERT_BY" => auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message
            ];
            DB::executeProcedure("MDA.CM_ENGINE.FUEL_RECEIVED_PROCESS", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                "status_code" => $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $response = ["status" => false, "status_code" => 99,  "status_message" => 'Approval data process error.'];
        }
        DB::commit();
        return $response;
    }

}
