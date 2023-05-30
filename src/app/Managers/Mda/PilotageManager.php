<?php

namespace App\Managers\Mda;


use App\Contracts\Mda\PilotageContract;
use App\Entities\Mda\Invoice;
use App\Entities\Mda\Pilotage;
use App\Helpers\HelperClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PilotageManager implements PilotageContract
{
    public $pVesselConditionId = null;
    public $pilotageTugsId = null;

    public function pilotageCud($action_type = null, $request = [], $id = null)
    {
        // TODO: Implement pilotageCud() method.
        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID" => ["value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                "P_VESSEL_ID" => $request->get("vessel_id"),
                "P_VESSEL_REG_NO" => $request->get("vessel_id"),
                "P_VESSEL_NEW_REG_NO" => $request->get("vessel_reg_no"),
//              "P_WORKING_TYPE_ID" => $request->get("working_type_id"),
                "P_WORKING_TYPE_ID" => $request->get("working_type_id") ? $request->get("working_type_id") : '20122912007014902', //Default: Mother
                "P_MOTHER_VESSEL_ID" => $request->get("mother_vessel_id"),
                "P_FILE_NO" => $request->get("file_no"),
                "P_PILOT_ID" => $request->get("pilot_id"),
                "P_PILOTAGE_TYPE_ID" => $request->get("pilotage_type_id"),
                "P_SCHEDULE_TYPE_ID" => $request->get("schedule_type_id"),
                "P_LOCAL_AGENT" => $request->get("local_agent"),
                "P_LAST_PORT" => $request->get("last_port"),
                "P_NEXT_PORT" => $request->get("next_port"),
                "P_PILOT_BORDED_AT" => date("Y-m-d H:i", strtotime($request->get("pilot_borded_at"))),
                "P_PILOT_LEFT_AT" => date("Y-m-d H:i", strtotime($request->get("pilot_left_at"))),
//              "P_PILOTAGE_FROM_TIME" => ($request->get("pilotage_type_id") == '2')? date("Y-m-d H:i", strtotime($request->get("pilot_borded_at"))):date("Y-m-d H:i", strtotime($request->get("pilotage_from_time"))),
//              "P_PILOTAGE_TO_TIME" => ($request->get("pilotage_type_id") == '2')?date("Y-m-d H:i", strtotime($request->get("pilot_left_at"))):date("Y-m-d H:i", strtotime($request->get("pilotage_to_time"))),
                "P_PILOTAGE_FROM_TIME" => date("Y-m-d H:i", strtotime($request->get("pilot_borded_at"))),
                "P_PILOTAGE_TO_TIME" => date("Y-m-d H:i", strtotime($request->get("pilot_left_at"))),
                "P_MOORING_FROM_TIME" => date("Y-m-d H:i", strtotime($request->get("mooring_from_time"))),
                "P_MOORING_TO_TIME" => date("Y-m-d H:i", strtotime($request->get("mooring_to_time"))),
                "P_MOORING_LINE_FORD" => $request->get("mooring_line_ford"),
                "P_MOORING_LINE_AFT" => $request->get("mooring_line_aft"),
//              "P_WORK_LOCATION_ID" => $request->get("work_location_id"),
//              "P_WORK_LOCATION_ID" => 2,
                "P_WORK_LOCATION_ID" => $request->get("work_location_id") ? $request->get("work_location_id"): 2 ,
                "P_SHIFTED_FROM" => $request->get("shifted_from"),
                "P_SHIFTED_TO" => $request->get("shifted_to"),
                "P_STERN_POWER_AVAIL" => $request->get("stern_power_avail"),
                "P_MASTER_SIGN_DATE" => date("Y-m-d H:i", strtotime($request->get("master_sign_date"))),
                "P_REMARKS" => $request->get("remarks"),
                "P_STATUS" => $request->get("status"),
                "P_MASTER_NAME" => $request->get("master_name"),
                "P_ARRIVAL_DATE" => date("Y-m-d H:i", strtotime($request->get("arrival_date"))),
                "P_GRT" => $request->get("grt"),
                "P_NRT" => $request->get("nrt"),
                "P_STAY_GUPTAKHAL_YN" => $request->get("stay_guptakhal_yn"),
                "P_ADDITIONAL_PILOT_ONE" => $request->get("additional_pilot_one"),
                "P_ADDITIONAL_PILOT_TWO" => $request->get("additional_pilot_two"),
                "P_DRAUGHT" => $request->get("draught"),
                "P_LENGTH" => $request->get("length"),
                "P_PILOTAGE_FROM_LOC" => $request->get("pilotage_from_loc"),
                "P_PILOTAGE_TO_LOC" => $request->get("pilotage_to_loc"),
                "P_CALL_SIGN" => $request->get("call_sign"),
                "P_CRW_OFFICER_INCL_MST_NUM" => $request->get("crw_officer_incl_mst_num"),
                "P_OWNER_ADDRESS" => $request->get("owner_address"),
                "P_GOOD_MOORING_LINE_NUMBER" => $request->get("good_mooring_line_number"), //? $request->get("good_mooring_line_number"): '',
                "P_FORD_GOOD_MOORING_NUMBER" => $request->get("ford_good_mooring_number"),
                "P_AFT" => $request->get("aft"),
                "P_STERN_AVL_POWER" => $request->get("stern_avl_power"),
                "P_IMMEDIATELY" => $request->get("immediately"),
//              "P_UNMOORING_FROM_TIME" => HelperClass::dateTimeFormatForDB($request->get("unmooring_from_time")),
//              "P_UNMOORING_TO_TIME" => HelperClass::dateTimeFormatForDB($request->get("unmooring_to_time")),
                "P_UNMOORING_FROM_TIME" => date("Y-m-d H:i", strtotime($request->get("mooring_from_time"))),
                "P_UNMOORING_TO_TIME" => date("Y-m-d H:i", strtotime($request->get("mooring_to_time"))),
                "P_DECK_CARGO" => 0,
//                "P_FIXED_MOORING" => 'N',
                "P_FIXED_MOORING" => $request->get("fixed_mooring"),
                "P_SWING_MOORING" => $request->get("swing_mooring"),
                "P_USER_ID" => auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message
            ];

            DB::executeProcedure("MDA.MDA_CORE_PROCE.PILOTAGES_CUD", $params);

//            dd($params);
//            P_PILOTAGE_FROM_LOC           IN     MDA.PILOTAGES.PILOTAGE_FROM_LOC%TYPE,
//      P_PILOTAGE_TO_LOC             IN     MDA.PILOTAGES.PILOTAGE_TO_LOC%TYPE,
//      P_CALL_SIGN                   IN     MDA.PILOTAGES.CALL_SIGN%TYPE,
//      P_CRW_OFFICER_INCL_MST_NUM    IN     MDA.PILOTAGES.CRW_OFFICER_INCL_MST_NUM%TYPE,
//      P_OWNER_ADDRESS               IN     MDA.PILOTAGES.OWNER_ADDRESS%TYPE,
//      P_GOOD_MOORING_LINE_NUMBER    IN     MDA.PILOTAGES.GOOD_MOORING_LINE_NUMBER%TYPE,
//      P_FORD_GOOD_MOORING_NUMBER    IN     MDA.PILOTAGES.FORD_GOOD_MOORING_NUMBER%TYPE,
//      P_AFT                         IN     MDA.PILOTAGES.AFT%TYPE,
//      P_STERN_AVL_POWER             IN     MDA.PILOTAGES.STERN_AVL_POWER%TYPE,
//      P_IMMEDIATELY                 IN     MDA.PILOTAGES.IMMEDIATELY%TYPE,
//      P_UNMOORING_FROM_TIME         IN     MDA.PILOTAGES.UNMOORING_FROM_TIME%TYPE,
//      P_UNMOORING_TO_TIME           IN     MDA.PILOTAGES.UNMOORING_TO_TIME%TYPE,

            if ($params["O_STATUS_CODE"] != "1") {
                DB::rollBack();
                throw new \Exception($params["O_STATUS_MESSAGE"] . " IN PILOTAGE");
            } else {
                if ($action_type != "D") {

                    //CUD pilotageTug
                    if ($request->get("tug") != null) {
                        foreach ($request->get("tug") as $tug) {
//                            if ($tug["tugId"] != "" && $tug["assistanceFrom"] != "" && $tug["assistanceTo"] != "" && $tug["isPrimary"] != "" && $tug["workLocation"] != "" ){
                            if ($tug["tugId"] != "" && $tug["assistanceFrom"] != "" && $tug["assistanceTo"] != "" && $tug["isPrimary"] != "") {
                                $acType = $action_type;
                                $status = "A";
                                //Conditions checking:
                                //Whether the row need to delete. actionType only set for delete
                                //User enter data then remove the row
                                //At the time of update there has pTtId and also has actionType, which means delete from database
                                //At the time of update or insert when new row added, no pTtId, no actionType which means insert
                                if (isset($tug["actionType"])) {
                                    if ($tug["pTtId"] == "") {
                                        continue;
                                    } else {
                                        $acType = "D";
                                        $status = "D";
                                    }
                                } elseif ($tug["pTtId"] == "") {
                                    $acType = "I";
                                }


                                $status_code3 = sprintf("%4000s", "");
                                $status_message3 = sprintf("%4000s", "");
                                $params3 = [
                                    "P_ACTION_TYPE" => $acType,
                                    "P_ID" => ["value" => &$tug["pTtId"], "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                                    "P_TUG_ID" => (int)$tug["tugId"],
                                    "P_PILOTAGE_ID" => $params["P_ID"],
                                    "P_ASSITANCE_FROM_TIME" => date("Y-m-d H:i:s", strtotime($tug["assistanceFrom"])),
                                    "P_ASSITANCE_TO_TIME" => date("Y-m-d H:i:s", strtotime($tug["assistanceTo"])),
                                    "P_STATUS" => $status,
                                    "P_PRIMARY_YN" => $tug["isPrimary"],
//                                   "P_WORK_LOCATION_ID" => $tug["workLocation"],
                                    "P_WORK_LOCATION_ID" => 2,
                                    "P_USER_ID" => (int)auth()->id(),
                                    "O_STATUS_CODE" => &$status_code3,
                                    "O_STATUS_MESSAGE" => &$status_message3
                                ];
//dd($params3);
                                DB::executeProcedure("MDA.MDA_CORE_PROCE.PILOTAGE_TUGS_CUD", $params3);
                                if ($params3["O_STATUS_CODE"] != "1") {
                                    DB::rollBack();
                                    throw new \Exception($params3["O_STATUS_MESSAGE"] . " IN TUG INFORMATION");
                                }
                            }
                        }
                    }

                    //CUD pilotage_vessel_condition

                    if ($request->get("vesselCondition") != null) {
                        foreach ($request->get("vesselCondition") as $condition) {
                            $status_code2 = sprintf("%4000s", "");
                            $status_message2 = sprintf("%4000s", "");
                            $params2 = [
                                "P_ACTION_TYPE" => $action_type,
                                "P_ID" => ["value" => &$condition["pVcTid"], "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                                "P_PILOTAGE_ID" => $params["P_ID"],
                                "P_VESSEL_CONDITION_ID" => (int)$condition["conditionId"],
                                "P_ANS_VALUE" => $condition["value"],
                                "P_STATUS" => "A",
                                "P_USER_ID" => auth()->id(),
                                "O_STATUS_CODE" => &$status_code2,
                                "O_STATUS_MESSAGE" => &$status_message2
                            ];

                            DB::executeProcedure("MDA.MDA_CORE_PROCE.PILOTAGE_VESSEL_CONDITIONS_CUD", $params2);

                            if ($params2["O_STATUS_CODE"] != "1") {
                                DB::rollBack();
                                throw new \Exception($params2["O_STATUS_MESSAGE"] . " IN VESSEL CONDITION");
                            }
                        }
                    }
                }
            }

            if ($params["O_STATUS_CODE"] != "1") {
                DB::rollBack();
                throw new \Exception($params["O_STATUS_MESSAGE"] . " IN Media_files");
            } else {
                if ($action_type != "D") {

                    for ($i = 0; $i < 3; $i++) {
                        $byteCode = "";
                        $fileExt = "";
                        $fTid = 0;
                        $status_code4 = sprintf("%4000s", "");
                        $status_message4 = sprintf("%4000s", "");
                        if ($i == 0) {
                            //dd($action_type);
                            $title = "MASTER_SIGN";
                            if ($request->hasFile("master_sign")) {
                                $fTid = ($request->get('pre_master_sign_id') != "") ? $request->get('pre_master_sign_id') : 0;
                                $action_type = ($request->get('pre_master_sign_id') != "") ? 'U' : 'I';
                                $files = $request->file("master_sign");
                                $byteCode = base64_encode(file_get_contents($files->getRealPath()));
                                $fileExt = $files->extension();
                            }
                        } elseif ($i == 1) {
                            $title = "ASSISTANT_SIGN";
                            if ($request->hasFile("assistant_sign")) {
                                $fTid = ($request->get('pre_assistant_sign_id') != "") ? $request->get('pre_assistant_sign_id') : 0;
                                $action_type = ($request->get('pre_assistant_sign_id') != "") ? 'U' : 'I';
                                $files = $request->file("assistant_sign");
                                $byteCode = base64_encode(file_get_contents($files->getRealPath()));
                                $fileExt = $files->extension();
                            }
                        } else {
                            $title = "CERTIFICATE_FILE";

                            if ($request->hasFile("certificate_form")) {
                                $fTid = ($request->get('pre_certificate_file_id') != "") ? $request->get('pre_certificate_file_id') : 0;
                                $action_type = ($request->get('pre_certificate_file_id') != "") ? 'U' : 'I';
                                $files = $request->file("certificate_form");
                                $byteCode = base64_encode(file_get_contents($files->getRealPath()));
                                $fileExt = $files->extension();
                            }
                        }

                        $params4 = [
                            "P_ACTION_TYPE" => $action_type,
                            "P_ID" => ["value" => &$fTid, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                            "P_FILES" => [
                                'value' => $byteCode,
                                'type' => \PDO::PARAM_LOB,
                            ],
                            "P_STATUS" => "A",
                            "P_CREATED_BY" => auth()->id(),
                            "P_UPDATED_BY" => auth()->id(),
                            "P_SOURCE_TABLE" => "PILOTAGES",
                            "P_REF_ID" => $params["P_ID"],
                            "P_TITLE" => $title,
                            "P_FILE_TYPE" => $fileExt,
                            "O_STATUS_CODE" => &$status_code4,
                            "O_STATUS_MESSAGE" => &$status_message4
                        ];

                        DB::executeProcedure("MDA.MDA_CORE_PROCE.MEDIA_FILES_CUD", $params4);
                        if ($params4["O_STATUS_CODE"] != "1") {
                            DB::rollBack();
                            throw new \Exception($params4["O_STATUS_MESSAGE"] . " IN MEDIA_FILES_CUD");
                        }
                    }
                }
            }

            DB::commit();
            $response = ["status" => true, "status_code" => $params["O_STATUS_CODE"], "status_message" => $params["O_STATUS_MESSAGE"], 'pilotage_id' => $params['P_ID']['value']];

        } catch (\Exception $ex) {
            $response = ["status" => false, "status_code" => "99", "status_message" => $ex->getMessage()];
            //           $response = ["status" => false, "status_code" => "99", "status_message" => 'Something went wrong. Please try again later.'];
        }

        //       dd($response);
        return $response;
    }

    /**
     * @return mixed
     */
    public function pilotageDatatable()
    {
        // TODO: Implement pilotageDatatable() method.
        return Pilotage::where("status", "!=", "D")
            ->with("working_type")
            ->with("pilotage_type")
            ->with("schedule_type")
            ->with("work_location")
            ->with("cpa_pilot")
            ->with("foreign_vessel")
            ->orderBy("created_at", "desc")
            ->get();
    }

    public function verifyCertificateDatatable($request)
    {
        $user_role = json_encode(Auth::user()->roles->pluck('role_key'));//dd($user_role);
        if (strpos($user_role, "mda-harbour-master") !== FALSE || strpos($user_role, "SUPER_ADMIN") !== FALSE) {
            return Pilotage::where("status", "=", $request->get("searched_status"))
                ->with("working_type")
                ->with("pilotage_type")
                ->with("schedule_type")
                ->with("work_location")
                ->with("cpa_pilot")
                ->with("foreign_vessel")
                ->orderBy('created_at', 'DESC')
                ->get();
        } else if (strpos($user_role, "MDA_DEPUTY_CONSERVATOR") !== FALSE) {
            return Pilotage::where("status", "=", $request->get("searched_status"))
                ->where('USER_ROLE', 'MDA_DEPUTY_CONSERVATOR')
                ->with("working_type")
                ->with("pilotage_type")
                ->with("schedule_type")
                ->with("work_location")
                ->with("cpa_pilot")
                ->with("foreign_vessel")
                ->orderBy('created_at', 'DESC')
                ->get();
        } else {
            return Pilotage::where("status", "=", $request->get("searched_status"))
                ->with("working_type")
                ->with("pilotage_type")
                ->with("schedule_type")
                ->with("work_location")
                ->with("cpa_pilot")
                ->with("foreign_vessel")
                ->orderBy('created_at', 'DESC')
                ->get();
        }

    }

    public function pilotageChangeStatus($action_type, $request, $id)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID" => (int)$request->get("pilotage_id"),
                "P_STATUS" => $request->get("status"),
                "P_VERIFY_REMARKS" => $request->get("remark"),
                "P_USER_ID" => (int)auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message
            ];
//dd($params);
            DB::executeProcedure("MDA.MDA_CORE_PROCE.PILOTAGES_APPROVAL_CUD", $params);
            $response = ["status" => true, "status_code" => $params["O_STATUS_CODE"], "status_message" => $params["O_STATUS_MESSAGE"]];

        } catch (\Exception $ex) {
            $response = ["status" => false, "status_code" => 99, "status_message" => $ex->getMessage()];
        }

        return $response;
    }


    public function pilotageInvoiceStore($traceId, $invoiceType, $invoiceData, $sourceTable)
    {
        // TODO: Implement pilotageInvoiceStore() method.

        try {
            $statusCode = sprintf("%4000s", "");
            $statusMessage = sprintf("%4000s", "");
            $id = null;
            $params = [
                "P_ACTION_TYPE" => "I",
                "P_ID" => ["value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                "P_TRACE_ID" => $traceId,
                "P_INVOICE_TYPE" => $invoiceType,
                "P_CREATED_BY" => auth()->id(),
                "P_UPDATED_BY" => auth()->id(),
                "P_SOURCE_TABLE" => $sourceTable,
                "P_INVOICE_DATA" => $invoiceData,
                "O_STATUS_CODE" => &$statusCode,
                "O_STATUS_MESSAGE" => &$statusMessage
            ];

            DB::executeProcedure("MDA.MDA_CORE_PROCE.INVOICE_CUD", $params);

            $response = ["status" => true, "status_code" => $params["O_STATUS_CODE"], "status_message" => $params["O_STATUS_MESSAGE"]];
        } catch (\Exception $ex) {
            $response = ["status" => false, "status_code" => 99, "status_message" => $ex->getMessage()];
        }

        return $response;
    }

//dom
    public function pilotageRegInfoUpdate($pilotageId)
    {
        // TODO: Implement pilotageInvoiceStore() method.

        try {
            $statusCode = sprintf("%4000s", "");
            $statusMessage = sprintf("%4000s", "");
            $id = null;

            $params = [
                "P_PILOTAGE_ID" => $pilotageId,
                "P_USER_ID" => Auth::id(),
                "O_STATUS_CODE" => &$statusCode,
                "O_STATUS_MESSAGE" => &$statusMessage
            ];

            DB::executeProcedure("VSL.VSL_UPDATE_REG_INFO", $params);

            $response = ["status" => true, "status_code" => $params["O_STATUS_CODE"], "status_message" => $params["O_STATUS_MESSAGE"]];
        } catch (\Exception $ex) {
            $response = ["status" => false, "status_code" => 99, "status_message" => $ex->getMessage()];
        }

        return $response;
    }


    public function getPilotageFee($request)
    {
        $pilotageFee = sprintf("%4000s", "");
        $shiftingFee = sprintf("%4000s", "");
        $pilotageNightNavFee = sprintf("%4000s", "");
        $guptaKhalFee = sprintf("%4000s", "");
        $totalPilotageFee = sprintf("%4000s", "");
        $totalShiftingFee = sprintf("%4000s", "");
        $params = [
            "P_PILOTAGE_TYPE" => $request->pilotage_type_id,
            "P_PILOTAGE_SCHEDULE_TYPE" => $request->schedule_type_id,
            "P_STAY_GUPTA_KHAL" => $request->stay_guptakhal_yn,
            "P_GRT" => $request->grt,
            "O_PILOTAGE_FEE" => &$pilotageFee,
            "O_SHIFTING_FEE" => &$shiftingFee,
            "O_PILOTAGE_NIGHT_NAV_FEE" => &$pilotageNightNavFee,
            "O_GUPTA_KHAL_FEE" => &$guptaKhalFee,
            "O_TOTAL_PILOTAGE_FEE" => &$totalPilotageFee,
            "O_TOTAL_SHIFTING_FEE" => &$totalShiftingFee
        ];

        DB::executeProcedure("MDA.MDA_CORE_PROCE.PILOTAGE_TARIF_CONFIGS", $params);
        return $params;
    }

    public function getTugFee($isPrimary, $workLocation, $grt, $assistanceFrom, $assistanceTo)
    {
        $tugFee = sprintf("%4000s", "");
        $totalFee = sprintf("%4000s", "");
        $totalDuration = sprintf("%4000s", "");

        $params = [
            "P_PRIMARY_YN" => $isPrimary,
            "P_WORK_LOCATION" => $workLocation,
            "P_GRT" => $grt,
            "P_ASSITANCE_FROM_TIME" => date("Y-m-d H:i", strtotime($assistanceFrom)),
            "P_ASSITANCE_TO_TIME" => date("Y-m-d H:i", strtotime($assistanceTo)),
            "O_TUG_FEE" => &$tugFee,
            "O_TOTAL_FEE" => &$totalFee,
            "O_TOTAL_DURATION" => &$totalDuration
        ];
//dd($params);
        DB::executeProcedure("MDA.MDA_CORE_PROCE.TUG_TARIF_CONFIGS", $params);
        return $params;
    }

    public function pilotageDetails($pilotageId)
    {
        // TODO: Implement pilotageDetails() method.
        return Pilotage::where("id", "=", $pilotageId)
            ->with("foreign_vessel")
            ->with("mother_vessel")
            ->with("working_type")
            ->with("pilotage_type")
            ->with("schedule_type")
            ->with("work_location")
            ->with("cpa_pilot")
            ->with("foreign_vessel")
            ->with("pilotage_vessel_condition")
            ->with("pilotage_tug")
            ->get();
    }

    public function approvedPilotages()
    {
        return Pilotage::where("status", "=", "C")
            ->with("working_type")
            ->with("pilotage_type")
            ->with("schedule_type")
            ->with("work_location")
            ->with("cpa_pilot")
            ->with("foreign_vessel")
            ->get();
    }

    public function invoiceData($pilotageId)
    {
        return Invoice::where("trace_id", "=", $pilotageId)->get();
    }

    public function getDryDockId()
    {
        $query = <<<QUERY
SELECT AGENCY_ID FROM VSL.SECDBMS_L_AGENCY WHERE AGENCY_SHORT_NAME = 60720
QUERY;
        return DB::selectOne($query);
    }
}
