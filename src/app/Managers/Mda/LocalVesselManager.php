<?php
namespace App\Managers\Mda;


use App\Contracts\Mda\LocalVesselContract;
use App\Entities\Mda\LocalVessel;
use Illuminate\Support\Facades\DB;

class LocalVesselManager implements LocalVesselContract
{
    public function localVesselCud($action_type=null, $request = [], $id=0)
    {
        $full = DB::table('VSL.SECDBMS_L_AGENCY')->where('agency_id',$request->get('agent_id'))->get(['agency_name','address'])->first();
        if(isset($full)){
            $aName = $full->agency_name;
            $aAddress = $full->address;
        }else{
            $aName = null;
            $aAddress = null;
        }
        try {
            if($request['reg_exp_date']!=''){
                $reg_exp_date = isset($request['reg_exp_date']) ? date('Y-m-d', strtotime($request['reg_exp_date'])) : '';
            }else{
                $reg_exp_date = '';
            }

            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_NAME" => $request->get("name"),
                "P_CALL_SIGN" => $request->get("call_sign"),
                "P_FLAG"=> $request->get("flag"),
                "P_GRT"  => $request->get("grt"),
                "P_NRT"   => $request->get("nrt"),
                "P_LOA"   => $request->get("loa"),
                "P_MAX_DRAUGHT" => $request->get("max_draught"),
                "P_TOTAL_CREW_OFFICER" => $request->get("total_crew_officer"),
                "P_OWNER_NAME"  => $aName,
                "P_OWNER_ADDRESS"  => $aAddress,
                "P_STATUS"   => $request->get("status"),
                "P_REG_NO"    => $request->get("reg_no"),
                "P_REG_EXP_DATE"  => $reg_exp_date,
                "P_REG_ISSUED_BY"  => auth()->id(),
                "P_REG_FILE"  => "",
                "P_AGENCY_ID"  => $request->get("agent_id"),
                "P_CREATED_BY" => ($action_type =='I')?auth()->id():'',
                "P_UPDATED_BY" => ($action_type !='I')?'':auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message
            ];
            DB::executeProcedure("MDA.MDA_CORE_PROCE.LOCAL_VESSELS_CUD", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];

            if ($action_type != "D"){
                $byteCode="";
                $fileExt = "";
                $fTid = 0;
                $status_code2 = sprintf("%4000s","");
                $status_message2 = sprintf("%4000s", "");
                $title = "LOCAL_VESSEL";

                if ($request->hasFile("reg_file")) {
                    $fTid = ($request->get('pre_reg_file_id') != "") ? $request->get('pre_reg_file_id') : 0;
                    $action_type = ($request->get('pre_reg_file_id') != "") ? 'U' : 'I';
                    $files = $request->file("reg_file");
                    $byteCode = base64_encode(file_get_contents($files->getRealPath()));
                    $fileExt = $files->extension();
                }

                $params2 = [
                    "P_ACTION_TYPE" => $action_type,
                    "P_ID" => ["value"=>&$fTid, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                    "P_FILES" => [
                        'value' => $byteCode,
                        'type'  => \PDO::PARAM_LOB
                    ],
                    "P_STATUS" => "A",
                    "P_CREATED_BY" => auth()->id(),
                    "P_UPDATED_BY" => auth()->id(),
                    "P_SOURCE_TABLE" => "LOCAL_VESSELS",
                    "P_REF_ID" => $params["P_ID"],
                    "P_TITLE" => $title,
                    "P_FILE_TYPE" => $fileExt,
                    "O_STATUS_CODE" => &$status_code2,
                    "O_STATUS_MESSAGE" => &$status_message2
                ];

                DB::executeProcedure("MDA.MDA_CORE_PROCE.MEDIA_FILES_CUD", $params2);
                if ($params2["O_STATUS_CODE"] != "1"){
                    throw new \Exception($params["O_STATUS_MESSAGE"]." IN MEDIA_FILES_CUD");
                }
            }
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }
    public function localVesselDatatable()
    {
        return LocalVessel::with('agent')->where('status', '!=', 'D')->orderBy("created_at", 'desc')->get();
    }


}
