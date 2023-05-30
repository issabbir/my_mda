<?php


namespace App\Managers\Mda;


use App\Contracts\Mda\TugsRegistrationContract;
use App\Entities\Mda\TugsRegistration;
use Illuminate\Support\Facades\DB;

class TugsRegistrationManager implements TugsRegistrationContract
{
    /**
     * @param null $action_type
     * @param array $request
     * @param null $id
     * @return mixed
     */
    public function tugsCud($action_type = null, $request = [], $id = null)
    {
        // TODO: Implement collectionTugs() method.
        try {
            $status_code=sprintf("%4000s","");
            $status_message=sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID" => ["value"=>&$id, "type"=>\PDO::PARAM_INPUT_OUTPUT, "length"=>255],
                /**Post Fields starts**/
                "P_NAME" => $request->post("tug_name"),
                "P_TUG_TYPE_ID" => $request->post("tug_type"),
                "P_CAPACITY" => $request->post("capacity"),
                /**Post Fields End**/
                "P_CREATED_BY"=> ($action_type == "I") ? auth()->id() : '',
                "P_UPDATED_BY"=> ($action_type != "I") ? "" : auth()->id(),
                "P_STATUS" => strtoupper($request->get("status")),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message

            ];

            DB::executeProcedure("MDA.MDA_CORE_PROCE.TUGS_CUD", $params);
            $response=["status"=>true, "status_code"=>$params["O_STATUS_CODE"],"status_message"=>$params["O_STATUS_MESSAGE"],"data"=>$params];

        }catch (\Exception $ex){
            $response = ["status"=>false, "stats_code"=>99, "status_message"=>$ex->getMessage()];
        }

        return $response;
    }

    /**
     * @return mixed
     */
    public function tugsDatatable()
    {
        // TODO: Implement collectionTugsDatatable() method.
        return TugsRegistration::where("status","!=", "D")->orderBy("created_at","desc")->with("tug_type")->get();
    }
}
