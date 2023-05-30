<?php
namespace App\Managers\Mda;


use App\Contracts\Mda\cashCollectionContract;
use App\Contracts\Mda\MooringChargeContract;
use App\Contracts\Mda\SmContract;
use App\Entities\Mda\CollectionSlip;
use App\Entities\Mda\MooringCharge;
use App\Entities\Mda\MooringVisit;
use App\Entities\Mda\SwingMooring;
use Illuminate\Support\Facades\DB;

class MooringChargeManager implements MooringChargeContract
{
    public function mooringChargeCud($action_type=null, $request = [], $id=0)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_MOORING_CHARGE_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_FORM_NO" => $request->get("form_no"),
                "P_COLLECTION_DATE" => $request->get("collection_date"),
                "P_COLLECTED_BY" => auth()->id(),
                "P_SLIP_TYPE_ID" => $request->get("slip_type_id"),
                "P_LOCAL_VESSEL_ID" => $request->get("local_vessel_id"),
                "P_MOORING_CHARGE_AMNT" => $request->get("mooring_charge_amnt"),
                "P_VAT_AMOUNT" => $request->get("vat_amount"),
                "P_PERIOD_FROM" => $request->get("period_from"),
                "P_PERIOD_TO" => $request->get("period_to"),
                "P_STATUS"   =>  $request->get("status"),
                "P_USER_ID" => auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            //dd($params);
            DB::executeProcedure("MDA.MOORING_CHARGE_CUD", $params);

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
    public function mooringChargeDatatable()
    {
        return MooringCharge::Where('status', '!=', 'D')->with("local_vessel")->with("slip_type")->orderBy("created_at", 'desc')->get();
    }

    public function approvedCollections()
    {
        // TODO: Implement approvedCollections() method.
        return MooringCharge::Where('status', '=', 'A')
            ->with("local_vessel")
            ->with("slip_type")
            ->orderBy("created_at", 'desc')
            ->get();
    }

    public function mooringChargeDetail($id)
    {
        // TODO: Implement cashCollectionDetal() method.
        return MooringCharge::where('status', '=', 'A')
            ->where('id','=', $id)
            ->with("local_vessel")
            ->with("slip_type")
            ->orderBy("created_at", 'desc')
            ->get();
    }
}




