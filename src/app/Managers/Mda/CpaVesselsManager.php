<?php


namespace App\Managers\Mda;


use App\Contracts\Mda\CpaVesselsContract;
use App\Entities\Mda\CpaVessel;
use Illuminate\Support\Facades\DB;

class CpaVesselsManager implements CpaVesselsContract
{

    /**
     * @param null $action_type
     * @param array $request
     * @param null $id
     * @return mixed
     */
    public function cpaVesselsCud($action_type = null, $request = [], $id = null)
    {
        // TODO: Implement cpaVesselsCud() method.
        try {
            $status_code=sprintf("%4000s","");
            $status_message=sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID" => ["value"=>&$id, "type"=>\PDO::PARAM_INPUT_OUTPUT, "length"=>255],
                /**Post Fields starts**/
                "P_NAME" => $request->post("cpaVessels_name"),
                "P_VESSEL_TYPE_ID" => $request->post("cpaVessels_type"),
                /**Post Fields End**/
                "P_CREATED_BY"=> ($action_type == "I") ? auth()->id() : '',
                "P_UPDATED_BY"=> ($action_type != "I") ? "" : auth()->id(),
                "P_STATUS" => strtoupper($request->get("status")),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message

            ];
            DB::executeProcedure("MDA.MDA_CORE_PROCE.CPA_VESSELS_CUD", $params);
            $response=["status"=>true, "status_code"=>$params["O_STATUS_CODE"],"status_message"=>$params["O_STATUS_MESSAGE"],"data"=>$params];

        }catch (\Exception $ex){
            $response = ["status"=>false, "stats_code"=>99, "status_message"=>$ex->getMessage()];
        }

        return $response;
    }

    /**
     * @return mixed
     */
    public function cpaVesselsDatatable()
    {
        // TODO: Implement cpaVesselsDatatable() method.
        return CpaVessel::where("status","!=", "D")->orderBy("created_at","desc")->with("vessel_type")->get();
    }
}
