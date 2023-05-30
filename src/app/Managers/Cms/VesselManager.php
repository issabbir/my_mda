<?php
namespace App\Managers\Cms;

use App\Contracts\Cms\VesselContract;
use App\Entities\Cms\CpaVessel;
use App\Entities\Cms\CpaVesselEngine;
use App\Entities\Cms\FuelConsumptionMst;
use App\Helpers\HelperClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class VesselManager implements VesselContract
{
    public function vesselStore($action_type = null, $request = [], $id = null)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => $id,
                "P_NAME" => $request->get("name"),
                "P_VESSEL_TYPE_ID" => $request->get("vessel_type_id"),
                "P_BHP" => $request->get('bhp'),
                "P_LOA" => $request->get('loa'),
                "P_BREADTH" =>$request->get("breadth"),
                "P_DEPTH" =>$request->get("depth"),
                "P_DRAFT" =>$request->get("draft"),
                "P_BUILD_YEAR" =>$request->get("build_year"),
                "P_BUILD_PLACE" =>$request->get("build_place"),
                "P_GRT" =>$request->get("grt"),
                "P_STATUS" =>$request->get("status"),
                "P_FUEL_TYPE_ID" =>$request->get("fuel_type_id"),
                "P_CREATED_BY" => auth()->id(),
                "P_INCHARGE_EMP_ID" =>$request->get("incharge_emp_id"),
                "P_RESERVED_FUEL" =>$request->get("reserved_fuel"),
                "P_DEPARTMENT_ID" =>$request->get("department_id"),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.CM_ENGINE.CPA_VESSELS_CUD", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {

            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function vesselData()
    {
        return CpaVessel::orderBy("created_at", 'desc')->get();
    }

    public function fuelConsumptionMstStore($action_type=null, $request = [], $id=null)
    {


        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_FUEL_CONSUMPTION_MST_ID"  => ["value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                "P_CPA_VESSEL_ID" => $request->get("cpa_vessel_id"),
                "P_CONSUMPTION_FROM" =>HelperClass::convert_date_form_ddmmyyyy_to_oracle_date($request->get('consumption_from')),
                "P_CONSUMPTION_TO" => HelperClass::convert_date_form_ddmmyyyy_to_oracle_date($request->get('consumption_to')),
                "p_CONSUMPTION_REF_NO" => $request->get("consumption_ref_no"),
                "P_CREATED_BY" => auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.CM_ENGINE.FUEL_CONSUMPTION_MST_CUD", $params);

            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
            if($params['O_STATUS_CODE'] === '1'){
                $response= $this->fuelConsumptionDtlStore($action_type,$request,$params['P_FUEL_CONSUMPTION_MST_ID']);
            }
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function fuelConsumptionDtlStore($action_type = null, $request = [], $id = null)
    {

            DB::beginTransaction();
            try {
                foreach ($request->get('vessel_engine_id') as $key=>$value) {
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params = [
                        "P_ACTION_TYPE" =>empty($request->get('fuel_consumption_dtl_id')[$key])?'I':'U',
                        "P_FUEL_CONSUMPTION_DLT_ID" =>isset($request->get('fuel_consumption_dtl_id')[$key])?$request->get('fuel_consumption_dtl_id')[$key]:'',
                        "P_FUEL_CONSUMPTION_MST_ID" =>$id,
                        "P_VESSEL_ENGINE_ID" => isset($request->get('vessel_engine_id')[$key])?$request->get('vessel_engine_id')[$key]:'',
                        "P_WORKING_HOURS" => isset($request->get('working_hours')[$key])?$request->get('working_hours')[$key]:'',
                        "P_HOURLY_CONSUMED_FUEL" => isset($request->get('hourly_consumed_fuel')[$key])?$request->get('hourly_consumed_fuel')[$key]:'',
                        "P_TOTAL_CONSUMED_FUEL" => isset($request->get('total_consumed_fuel')[$key])?$request->get('total_consumed_fuel')[$key]:'',
                        "P_REMARKS" => isset($request->get('item_remarks')[$key])?$request->get('item_remarks')[$key]:'',
                        "P_CREATED_BY" =>auth()->id(),
                        "O_STATUS_CODE" => &$status_code,
                        "O_STATUS_MESSAGE" => &$status_message,
                    ];
                    DB::executeProcedure("MDA.CM_ENGINE.FUEL_CONSUMPTION_DTL_CUD", $params);
                    if($params['O_STATUS_CODE']==='99'){
                        DB::rollback();
                        $response = [
                            "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                            "status_code" => $params['O_STATUS_CODE'],
                            "fuel_consumption_mst_id" =>  $id,
                            "data" => $params,
                            "status_message" => $params['O_STATUS_MESSAGE']
                        ];
                        return $response;
                    }
                }
                $response = [
                    "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                    "status_code" => $params['O_STATUS_CODE'],
                    "fuel_consumption_mst_id" =>  $id,
                    "data" => $params,
                    "status_message" => $params['O_STATUS_MESSAGE']
                ];
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $response = ["status" => false, "status_code" => 99,  "fuel_consumption_mst_id" =>  $id, "status_message" => 'Please try again later.'];
            }
        return $response;
    }

    public function fuelConsumptionData($request = [])
    {
        return FuelConsumptionMst::where('cpa_vessel_id',$request->get('vessel_id'))
            ->orderBy("created_date", 'desc')
            ->get();
    }
    public function vesselEngineMappingStore($action_type = null, $request = [], $id = null)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_VESSEL_ENGINE_ID"  => $id,
                "P_CPA_VESSEL_ID" => $request->get("cpa_vessel_id"),
                "P_ENGINE_TYPE_ID" => $request->get("engine_type_id"),
                "P_MAX_FUEL_CAPACITY" => $request->get('max_fuel_capacity'),
                "P_HORSE_POWER" => $request->get('horse_power'),
                "P_FUEL_TYPE_ID" =>$request->get("fuel_type_id"),
                "P_RESERVED_FUEL" =>$request->get("reserved_fuel"),
                "P_STATUS" =>$request->get("status"),
                "P_CREATED_BY" => auth()->id(),
                "P_HOURLY_CONSUMED_FUEL" => $request->get("hourly_consumed_fuel"),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.CM_ENGINE.CPA_VESSEL_ENGINE_CUD", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function vesselEngineMappingData($request = [])
    {
        return CpaVesselEngine::where('cpa_vessel_id',$request->get('vessel_id'))
            ->orderBy("created_date", 'asc')
            ->get();
    }

    public function consumptionApprovalStore($request = [],$id=null)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_FUEL_CONSUMPTION_MST_ID" => ($request->get('fuel_consumption_mst_id'))?$request->get('fuel_consumption_mst_id'):$id,
                "p_work_flow_master_id" => (HelperClass::checkIsExternalModule())?HelperClass::checkIsExternalModule():1,
                "P_CREATED_BY" => auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.CM_ENGINE.WF_MAPPING_ENTRY", $params);

            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function fuelConsumptionStoreWithApproval($action_type=null, $request = [], $id=null){
       $response= $this->fuelConsumptionMstStore($action_type,$request,$id);
       if($response['status_code']==='1'){
           $approval_response=$this->consumptionApprovalStore($request,$response['fuel_consumption_mst_id']['value']);
           if($approval_response['status_code']==='1'){
               return  ["status" => true,
                        "status_code" => 1,
                        "status_message" => 'Fuel consumption has been submitted and send to the approval panel'];
           }else{
               return $approval_response;
           }

       }else{
           return $response;
       }

    }

}
