<?php
namespace App\Managers\Cms;

use App\Contracts\Cms\SettingsContract;
use App\Entities\Cms\LFuelType;
use App\Entities\Cms\LPlacement;
use App\Entities\Cms\LVesselEngineType;
use App\Entities\Cms\Shifting;
use App\Entities\Pmis\Employee\Employee;
use App\Helpers\HelperClass;
use Illuminate\Support\Facades\DB;

class SettingsManager implements SettingsContract
{
    public function fuelTypesCud($action_type=null, $request = [], $id=null)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_FUEL_TYPE_ID"  => $id,
                "P_FUEL_TYPE_NAME" => $request->get("fuel_type_name"),
                "P_FUEL_TYPE_NAME_BN" => $request->get("fuel_type_name_bn"),
                "p_STATUS" => $request->get('status'),
                "P_CREATED_BY" => auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.CM_ENGINE.CM_FUEL_TYPES_CUD", $params);
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

    public function fuelTypesData()
    {
        return LFuelType::orderBy("created_date", 'desc')->get();
    }

    public function placementCud($action_type=null, $request = [], $id=null)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_PLACEMENT_ID"  => $id,
                "P_PLACEMENT_NAME"  => $request->get("placement_name"),
                "P_DESCRIPTION" => $request->get("description"),
                "P_STATUS" => $request->get('status'),
                "P_CREATED_BY" => auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.CM_ENGINE.CM_PLACEMENT_CUD", $params);

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

    public function placementData()
    {
        return LPlacement::orderBy("created_date", 'desc')->get();
    }



    public function vesselEngineTypeCud($action_type=null, $request = [], $id=0)
    {

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ENGINE_ID"  => $id,
                "P_ENGINE_NAME" => $request->get("engine_name"),
                "P_STATUS" => $request->get('status'),
                "P_CREATED_BY" => auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.CM_ENGINE.CM_VESSEL_ENGINE_CUD", $params);
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

    public function vesselEngineTypeData()
    {
        return LVesselEngineType::orderBy("created_date", 'desc')->get();
    }

    public function shiftingCud($action_type = null, $request = [], $id = null)
    {

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_SHIFTING_ID"  => $id,
                "P_SHIFT_NAME" => $request->get("shift_name"),
                "P_SHIFTING_START_TIME" =>(!$request->get("shifting_start_time"))?'':date("H:i", strtotime($request->get("shifting_start_time"))),
                "P_SHIFTING_END_TIME" =>(!$request->get("shifting_end_time"))?'':date("H:i", strtotime($request->get("shifting_end_time"))),
                "P_EFFECTIVE_FROM_DATE" =>(!$request->get("effective_from_date"))?'':date("Y-m-d", strtotime($request->get("effective_from_date"))),
                "P_EFFECTIVE_TO_DATE" =>(!$request->get("effective_to_date"))?'':date("Y-m-d", strtotime($request->get("effective_to_date"))),
                "P_STATUS" => $request->get('status'),
                "P_CREATED_BY" =>auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.CM_ENGINE.CM_SHIFTING_CUD", $params);
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

    public function shiftingData()
    {
        return Shifting::orderBy("created_date", 'desc')->get();
    }

}
