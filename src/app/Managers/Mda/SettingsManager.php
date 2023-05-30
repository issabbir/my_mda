<?php
namespace App\Managers\Mda;


use App\Contracts\Mda\SettingsContract;
use App\Entities\Mda\Cargo;
use App\Entities\Mda\LCollectionSlipType;
use App\Entities\Mda\LCpaVesselType;
use App\Entities\Mda\LPilotageWorkLocation;
use App\Entities\Mda\LPsScheduleType;
use App\Entities\Mda\LVesselWorkingType;
use App\Entities\Mda\LPilotageType;
use App\Entities\Mda\LTugType;
use App\Entities\Mda\LVesselCondition;
use Illuminate\Support\Facades\DB;

class SettingsManager implements SettingsContract
{
    public function collectionSlipTypeCud($action_type=null, $request = [], $id=0)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_NAME" => $request->get("name"),
                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?'':auth()->id(),
                "P_STATUS" => strtoupper($request->get("status")),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_CORE_PROCE.COLLECTION_SLIP_TYPES_CUD", $params);
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
    public function collectionSlipTypeDatatable()
    {
        return LCollectionSlipType::Where('status', '!=', 'D')->orderBy("created_at", 'desc')->get();
    }

    public function cpaVesselTypeCud($action_type = null, $request = [], $id = null)
    {
        // TODO: Implement cpaVesselTypeCud() method.
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_NAME" => $request->get("name"),
                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?'':auth()->id(),
                "P_STATUS" => strtoupper($request->get("status")),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];

            DB::executeProcedure("MDA.MDA_CORE_PROCE.CPA_VESSEL_TYPE_CUD", $params);
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

    public function cpaVesselTypeDatatable()
    {
        // TODO: Implement cpaVesselTypeDatatable() method.
        return LCpaVesselType::Where('status', '!=', 'D')->orderBy("created_at", 'desc')->get();
    }

    public function pilotageTypeCud($action_type=null, $request = [], $id=0)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_NAME" => $request->get("name"),
                "P_DESCRIPTION" => $request->get("description"),
                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?'':auth()->id(),
                "P_STATUS" => strtoupper($request->get("status")),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_CORE_PROCE.PILOTAGE_TYPES_CUD", $params);
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

    public function pilotageTypeDatatable()
    {
        return LPilotageType::Where('status', '!=', 'D')->orderBy("created_at", 'desc')->get();
    }

    public function tugTypeCud($action_type=null, $request = [], $id=0)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id,
                            "type" => \PDO::PARAM_INPUT_OUTPUT,
                            "length" => 255
                ],
                "P_NAME" => $request->get("name"),
                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?'':auth()->id(),
                "P_STATUS" => strtoupper($request->get("status")),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_CORE_PROCE.TUG_TYPES_CUD", $params);
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
    public function tugTypeDatatable()
    {
        return LTugType::Where('status', '!=', 'D')->orderBy("created_at", 'desc')->get();
    }

    public function cargoCud($action_type=null, $request = [], $id=0)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id,
                    "type" => \PDO::PARAM_INPUT_OUTPUT,
                    "length" => 255
                ],
                "P_NAME" => $request->get("name"),
                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?'':auth()->id(),
                "P_STATUS" => strtoupper($request->get("status")),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.CARGO_CUD", $params);
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
    public function cargoDatatable()
    {
        return Cargo::Where('status', '!=', 'D')->orderBy("created_at", 'desc')->get();
    }

    public function psScheduleTypeCud($action_type = null, $request = [], $id = null)
    {
        // TODO: Implement psScheduleTypeCud() method.
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],

                "P_NAME" => $request->get("name"),
                "P_DESCRIPTION" => $request->get("description"),
                "P_CREATED_BY" => (int)($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => (int)($action_type !='I')?'':auth()->id(),
                "P_STATUS" => strtoupper($request->get("status")),
                "P_START_TIME" => date("H:i:s", strtotime($request->get("start_time"))),
                "P_END_TIME" => date("H:i:s", strtotime($request->get("end_time"))),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message
            ];

            DB::executeProcedure("MDA.MDA_CORE_PROCE.PILOTAGE_SCHEDULE_TYPES_CUD", $params);

            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];

        }catch (\Exception $ex){
            $response = ["status" => false, "status_code" => 99, "status_message" =>'Failed, Please try again later.'];
        }

        return $response;
    }

    public function psScheduleTypeDatatable()
    {
        // TODO: Implement psScheduleTypeDatatable() method.
        return LPsScheduleType::Where('status', '!=', 'D')->orderBy("created_at", 'desc')->get();
    }

    public function vesselWorkingTypeCud($action_type = null, $request = [], $id = null)
    {
        // TODO: Implement vesselWorkingTypeCud() method.
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_NAME" => $request->get("name"),
                "P_DESCRIPTION" => $request->get("description"),
                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?'':auth()->id(),
                "P_STATUS" => strtoupper($request->get("status")),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_CORE_PROCE.VESSEL_WORKING_TYPES_CUD", $params);
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

    public function vesselWorkingTypeDatatable()
    {
        // TODO: Implement vesselWorkingTypeDatatable() method.
        return LVesselWorkingType::Where('status', '!=', 'D')->orderBy("created_at", 'desc')->get();
    }
    public function vesselConditionCud($action_type=null, $request = [], $id=0)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_TITLE" => $request->get("title"),
                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?'':auth()->id(),
                "P_STATUS" => strtoupper($request->get("status")),
                "P_VALUE_TYPE" =>strtoupper($request->get("value_type")),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_CORE_PROCE.VESSEL_CONDITIONS_CUD", $params);
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

    public function vesselConditionDatatable()
    {
        return LVesselCondition::Where('status', '!=', 'D')->orderBy("created_at", 'desc')->get();
    }

    /**
     * @param null $action_type
     * @param array $request
     * @param null $id
     * @return mixed
     */
    public function pilotageWorkLocationCud($action_type = null, $request = [], $id = null)
    {
        // TODO: Implement pilotageWorkLocationCud() method.
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],

                "P_NAME" => $request->get("name"),
                "P_DESCRIPTION" => $request->get("description"),

                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?'':auth()->id(),
                "P_STATUS" => strtoupper($request->get("status")),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.MDA_CORE_PROCE.PILOTAGE_WORK_LOCATIONS_CUD", $params);
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

    /**
     * @return mixed
     */
    public function pilotageWorkLocationDatatable()
    {
        // TODO: Implement pilotageWorkLocationDatatable() method.
        return LPilotageWorkLocation::Where('status', '!=', 'D')->orderBy("created_at", 'desc')->get();
    }
}
