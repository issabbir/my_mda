<?php
namespace App\Managers\Mda;


use App\Contracts\Mda\cashCollectionContract;
use App\Contracts\Mda\SmContract;
use App\Entities\Mda\CollectionSlip;
use App\Entities\Mda\MooringVisit;
use App\Entities\Mda\SwingMooring;
use Illuminate\Support\Facades\DB;

class cashcCollectionManager implements cashCollectionContract
{
    public function cashCollectionCud($action_type=null, $request = [], $id=0)
    {//date("d").date("m").date("Y").date("H").date("i").date("s");
        //dd(date("Yd"));
        $postData = $request->post();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_FORM_NO" => isset($postData['form_no']) && $postData['form_no']!=''? $postData['form_no']: date("YHis"),
                "P_COLLECTION_DATE" => $request->get("collection_date"),///
                "P_COLLECTED_BY" => auth()->id(),///
                "P_SLIP_TYPE_ID" => $request->get("slip_type_id") ? $request->get("slip_type_id") : 20122912003014907,///
                "P_LOCAL_VESSEL_ID" => $request->get("local_vessel_id"),////
                "P_PORT_DUES_AMOUNT" => $request->get("dues_select") == 1 ? $request->get("dues_amount") : 0,//$request->get("port_dues_amount"),
                "P_RIVER_DUES_AMOUNT" => $request->get("dues_select") == 2 ? $request->get("dues_amount") : 0,//$request->get("river_dues_amount"),
                "P_VAT_AMOUNT" => $request->get("vat_amount"),////
                "P_OTHER_DUES_TITLE" => $request->get("other_dues_title"),////
                "P_OTHER_DUES_AMOUNT" => $request->get("other_dues_amount"),////
                "P_PERIOD_FROM" => $request->get("period_from"),////
                "P_PERIOD_TO" => $request->get("period_to"),////
                "P_STATUS"   =>  $request->get("status"),////
                "P_BARGE_FEE_AMOUNT"   =>  $request->get("dues_select") == 3 ? $request->get("dues_amount") : 0,//$request->get("license_bill_amount"),
                "P_LICENSE_BILL_AMOUNT"   =>  $request->get("dues_select") == 4 ? $request->get("dues_amount") : 0,//$request->get("license_bill_amount"),
                "P_OFFICE_ID" => $request->get("office_id"),////
                "P_BOOK_NO" => isset($postData['book_no']) && $postData['book_no']!=''? $postData['book_no']: date("Yd"),
                "P_GRT" => $request->get("grt"),////
                "P_DUES_SELECT" => $request->get("dues_select"),////
                "P_USER_ID" => auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            //dd($params);
            DB::executeProcedure("MDA.MDA_CORE_PROCE.COLLECTION_SLIPS_CUD", $params);

            /*if ($params["O_STATUS_CODE"] == '1' && $params["P_STATUS"] == '1'){
                $data = $this->cashCollectionDetail($params['P_ID']);
                $slips = [
                    [
                        ['LABEL'] => "FORM NO",
                        ['VALUE'] => $data->form_no
                    ],

                    [
                        ['LABEL'] => "COLLECTION DATE",
                        ['VALUE'] => $data->collection_date
                    ],

                    [
                        ['LABEL'] => "COLLECTED BY",
                        ['VALUE'] => $data->collected_by
                    ],

                    [
                        ['LABEL'] => "SLIP TYPE",
                        ['VALUE'] => $data->slip_type->name
                    ],

                    [
                        ['LABEL'] => "LOCAL VESSEL",
                        ['VALUE'] => $data->local_vessel->name
                    ],

                    [
                        ['LABEL'] => "PORT DUES AMOUNT",
                        ['VALUE'] => $data->port_dues_amount
                    ],

                    [
                        ['LABEL'] => "RIVER DUES AMOUNT",
                        ['VALUE'] => $data->river_dues_amount
                    ],

                    [
                        ['LABEL'] => "VAT AMOUNT",
                        ['VALUE'] => $data->vat_amount
                    ],

                    [
                        ['LABEL'] => "OTHER DUES TITLE",
                        ['VALUE'] => $data->other_dues_title
                    ],

                    [
                        ['LABEL'] => "OTHER DUES AMOUNT",
                        ['VALUE'] => $data->other_dues_amount
                    ],

                    [
                        ['LABEL'] => "PERIOD FROM",
                        ['VALUE'] =>
                    ],

                    [
                        ['LABEL'] => "PERIOD TO",
                        ['VALUE'] =>
                    ],
                ];
            }*/

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
    public function cashCollectionDatatable()
    {
        return CollectionSlip::Where('status', '!=', 'D')->with("local_vessel")->with("slip_type", "office")->orderBy("created_at", 'desc')->get();
    }

    public function approvedCollections()
    {
        // TODO: Implement approvedCollections() method.
        return CollectionSlip::Where('status', '=', 'A')
            ->with("local_vessel")
            ->with("slip_type")
            ->orderBy("created_at", 'desc')
            ->get();
    }

    public function cashCollectionDetail($id)
    {
        // TODO: Implement cashCollectionDetal() method.
        return CollectionSlip::where('status', '=', 'A')
            ->where('id','=', $id)
            ->with("local_vessel")
            ->with("slip_type")
            ->orderBy("created_at", 'desc')
            ->get();
    }
}




