<?php


namespace App\Managers\Mda;


use App\Contracts\Mda\JettyServiceContract;
use App\Entities\Mda\VslJettyService;
use Illuminate\Support\Facades\DB;

class JettyServiceManager implements JettyServiceContract
{

    public function jettyServiceCud($request,$action_type = null, $id = null)
    {
//        dd($request);
        // TODO: Implement jettyServiceCud() method.
        DB::beginTransaction();
        try{
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $arrivel_date = ($request->get("arival_date") != "") ? date("Y-m-d", strtotime($request->get("arival_date"))) : "";
            $arrival_time = ($request->get("arival_time") != "") ? date("Y-m-d", strtotime($request->get("arival_time"))) : "";
            $berthing_Date = ($request->get("berthing_at") != "") ? date("Y-m-d", strtotime($request->get("berthing_at"))) : "";
            $departer_Date = date("Y-m-d", strtotime($request->get("depar_date")));
            $approved_date = ($request->get("approved_date") != "") ? date("Y-m-d", strtotime($request->get("approved_date"))) : "";
            $params = [
            "P_ACTION_TYPE"   => $action_type,
            "P_TRANSACTION_ID"  => ["value"=>&$id, "type"=>\PDO::PARAM_INPUT_OUTPUT, "length"=>255],
            "P_VESSEL_NO"  => $request->get("vessel_no"),
            "P_NEW_REG_NO"  => $request->get("new_reg_no")?$request->get("new_reg_no"):'',
            "P_REGISTRATION_NO"  => $request->get("reg_no")?$request->get("reg_no"):'',
            "P_REGISTRATION_DATE"  => ($request->get("reg_date") != "") ? date("Y-m-d", strtotime($request->get("reg_date"))) : "",
            "P_ARIVAL_DATE"  =>$arrivel_date,
            "P_ARIVAL_TIME"  =>  $arrival_time,
            "P_BERTHING_DATE"  =>  $berthing_Date,
            "P_DEPAR_DATE"  =>  $departer_Date,
            "P_NO_OF_OCCATION"  => $request->get("no_of_occation")?$request->get("no_of_occation"):'',
            "P_JETTY_CRANE_USED"   => $request->get("jetty_crane_used")?$request->get("jetty_crane_used"):$request->get("m_crane_used"),
            "P_JETTY_CRANE_USED_SHIFT"  => $request->get("jetty_crane_used_shift")?$request->get("jetty_crane_used_shift"):'',
            "P_JETTY_CRANE_USED_NOT"   => $request->get("jetty_crane_used_not")?$request->get("jetty_crane_used_not"):'',
            "P_JETTY_CRANE_USED_NOT_SHIFT"  => $request->get("jetty_crane_used_not_shift")?$request->get("jetty_crane_used_not_shift"):'',
            "P_JETTY_CRANE_CAN1"   => $request->get("jetty_crane_can1")?$request->get("jetty_crane_can1"):'',
            "P_JETTY_CRANE_CAN2"  => $request->get("jetty_crane_can2")?$request->get("jetty_crane_can2"):'',
            "P_M_CRANE_USED"   => $request->get("m_crane_used")?$request->get("m_crane_used"):'',
            "P_DERRICK_USED"   => $request->get("derrick_used")?$request->get("derrick_used"):'',
            "P_QTY_L_2"     => $request->get("qty_l_2")?$request->get("qty_l_2"):'',
            "P_QTY_L_2_H"   => $request->get("qty_l_2_h")?$request->get("qty_l_2_h"):'',
            "P_QTY_L_4"     => $request->get("qty_l_4")?$request->get("qty_l_4"):'',
            "P_QTY_L_4_H"    => $request->get("qty_l_4_h")?$request->get("qty_l_4_h"):'',
            "P_QTY_E_2"      => $request->get("qty_e_2")?$request->get("qty_e_2"):'',
            "P_QTY_E_2_H"    => $request->get("qty_e_2_h")?$request->get("qty_e_2_h"):'',
            "P_QTY_E_4"      => $request->get("qty_e_4")?$request->get("qty_e_4"):'',
            "P_QTY_E_4_H"    => $request->get("qty_e_4_h")?$request->get("qty_e_4_h"):'',
            "P_QTY_L_2_EX"   => $request->get("qty_l_2_ex")?$request->get("qty_l_2_ex"):'',
            "P_QTY_L_2_H_EX"  => $request->get("qty_l_2_h_ex")?$request->get("qty_l_2_h_ex"):'',
            "P_QTY_L_4_EX"    => $request->get("qty_l_4_ex")?$request->get("qty_l_4_ex"):'',
            "P_QTY_L_4_H_EX"  => $request->get("qty_l_4_h_ex")?$request->get("qty_l_4_h_ex"):'',
            "P_QTY_E_2_EX"    => $request->get("qty_e_2_ex")?$request->get("qty_e_2_ex"):'',
            "P_QTY_E_2_H_EX"  => $request->get("qty_e_2_h_ex")?$request->get("qty_e_2_h_ex"):'',
            "P_QTY_E_4_EX"    => $request->get("qty_e_4_ex")?$request->get("qty_e_4_ex"):'',
            "P_QTY_E_4_H_EX"   => $request->get("qty_e_4_h_ex")?$request->get("qty_e_4_h_ex"):'',
            "P_REMARKS"      => $request->get("remarks")?$request->get("remarks"):'',
//            "P_STATUS"    => $request->get("status"),
            "P_STATUS"    => 'A',
            "P_JETTY_STATUS"    => $request->get("jetty_status")?$request->get("jetty_status"):'',
            "P_INSERT_BY"   => ($action_type =='I')?auth()->id():'',
            "P_UPDATE_BY"   => ($action_type =='I')?'':auth()->id(),
            "P_BILL_TYPE_ID"  => $request->get("bill_type_id")?$request->get("bill_type_id"):'',
            "P_AGENCY_ID"   => $request->get("agent_id"),
            "P_APPROVED_YN"    => $request->get("approved_yn")?$request->get("approved_yn"):'',
            "P_APPROVED_BY"    => $request->get("approved_by")?$request->get("approved_by"):'',
            "P_APPROVED_DATE"  => $approved_date,
            "P_JETTY_ID"    => $request->get("jetty_id"),
            "P_WATER_BARGE"   => $request->get("water_barge")?$request->get("water_barge"):'',
            "P_WATER_MAIN"   => $request->get("water_main")?$request->get("water_main"):'',
            "P_WATER_MAIN_CANCEL"  => $request->get("water_main_cancel")?$request->get("water_main_cancel"):'',
            "P_IS_IMPORT"  => $request->get("is_import")?$request->get("is_import"):'',
            "P_EX_RATE"   => $request->get("ex_rate")?$request->get("ex_rate"):'',
            "P_REF_PILOTAGE_ID"  => $request->get("ref_pilotage_id")?$request->get("ref_pilotage_id"):'',
//            "P_BILL_YN"   => $request->get('bill_yn'),
//            "P_REF_BILL_NO" => $request->get("ref_bill_no")?$request->get("ref_bill_no"):'',
            "O_STATUS_CODE"   => &$status_code,
            "O_STATUS_MESSAGE"   => &$status_message
            ];
//dd($params);
            DB::executeProcedure("MDA.JETTY_SERVICE_CUD", $params);
//            dd($params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
            if ($params['O_STATUS_CODE'] === '1') {
//                if ($request->get("status") === 'A') {
                    $status_code2 = sprintf("%4000s", "");
                    $status_message2 = sprintf("%4000s", "");
                    $params2 = [
                        'P_TRANSACTION_ID'   =>$params['P_TRANSACTION_ID']['value'],
                        'O_STATUS_CODE'      =>&$status_code2,
                        'O_STATUS_MESSAGE'   =>&$status_message2
                    ];
                    DB::executeProcedure("MDA.JETTY_SERVICE_MDA_TO_VSL", $params2);

                    $response = [
                        "status" => $params2['O_STATUS_CODE'] == 1,
                        "status_code" => $params2['O_STATUS_CODE'],
                        "data" => $params,
                        "status_message" => $params2['O_STATUS_MESSAGE']
                    ];
//                }
//                else {
//                    $response = [
//                        "status" => true,
//                        "status_code" => $params['O_STATUS_CODE'],
//                        "data" => $params,
//                        "status_message" => $params['O_STATUS_MESSAGE']
//                    ];
//                }

            } else {
                $response = [
                    "status" => false,
                    "status_code" => $params['O_STATUS_CODE'],
                    "data" => $params,
                    "status_message" => $params['O_STATUS_MESSAGE']
                ];
            }
            DB::commit();
        }catch (\Exception $ex){
            DB::rollback();
//            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
            $response = ["status" => false, "status_code" => 99, "status_message" => $ex->getMessage()];
        }
        return $response;
    }

    public function jettyServiceDatatable()
    {
        return DB::select("SELECT vj.*, VR.VESSEL_NAME, VR.SHIPPING_AGENT_NAME
  FROM MDA.VSL_JETTY_SERVICE vj
       LEFT JOIN vtmis.vessel_registration vr ON VJ.VESSEL_NO = VR.ID
 WHERE vj.status != 'D'
 ORDER BY INSERT_DATE DESC");

    }
}
