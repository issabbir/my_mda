<?php

namespace App\Http\Controllers\Mea\Vms;

use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuelConsumptionController extends Controller
{
    private $flashMessageManager;

    public function __construct(FlashMessageManager $flashMessageManager)
    {
        $this->flashMessageManager = $flashMessageManager;
    }
    public function index()
    {
        $data = [
            'get_vehicle_reg_no_list' => $this->commonDropDownLookupsList('MEA.vm_VEHICLE_pkg.get'),
            'get_driver_reg_list' => $this->commonDropDownLookupsList('VM_DRIVER_PKG.get_driver_list'),
            'loadDecisionDropdown' => $this->loadDecisionDropdown(),
            'loadDepotTypeDropdown' => $this->loadDepotTypeDropdown('Y'),
            'get_fuel_types' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_fuel_types',4),

            'get_fuel_consumption_types' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_fuel_consumption_types',1),
            'get_work_type_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_work_type',1),
//            'get_engine_type_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_engine_type'),
            'get_fuel_unit_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_fuel_unit',1),
            'get_refuel_frequency_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_refuel_frequency',1),
        ];

        return view('mea.vms.fuelconsumption.index', compact('data'));
    }

    public function commonDropDownLookupsList($look_up_name = null,$column_selected = null,$fetch_single_colid = null){
        $query = "Select ".$look_up_name."('".$fetch_single_colid."') from dual" ;
        $entityList = DB::select($query);
        $entityOption = [];
        $entityOption[] = "<option value=''>Please select an option</option>";
        foreach ($entityList as $item) {
            $entityOption[] = "<option value='".$item->pass_value."'".($column_selected == $item->pass_value ? 'selected':'').">".$item->show_value."</option>";
        }
        return $entityOption;
    }

    public function loadDecisionDropdown($column_selected = null){
        $query = array(
            array(
                'pass_value'=>'Y',
                'show_value'=>'Yes'
            ),
            array(
                'pass_value'=>'N',
                'show_value'=>'No'
            ),
        ) ;

        $entityList = $query;
        $entityOption = [];
        $entityOption[] = "<option value=''>Please select an option</option>";
        foreach ($entityList as $item) {
            $entityOption[] = "<option value='".$item['pass_value']."'".($column_selected == $item['pass_value'] ? 'selected':'').">".$item['show_value']."</option>";
        }
        return $entityOption;
    }

    public function loadDepotTypeDropdown($column_selected = null){
        $query = array(
            array(
                'pass_value'=>'Y',
                'show_value'=>'CPA'
            ),
            array(
                'pass_value'=>'N',
                'show_value'=>'Outside'
            ),
        ) ;

        $entityList = $query;
        $entityOption = [];
        $entityOption[] = "<option value=''>Please select an option</option>";
        foreach ($entityList as $item) {
            $entityOption[] = "<option value='".$item['pass_value']."'".($column_selected == $item['pass_value'] ? 'selected':'').">".$item['show_value']."</option>";
        }
        return $entityOption;
    }

    private function findInsertedData($pkgFunction,$primaryId = null,$multipleRow=null){
        //$querys = "SELECT MEA.vm_VEHICLE_pkg.get('".$vehicle_id."') from dual" ;
        $querys = "SELECT ".$pkgFunction."('".$primaryId."') from dual" ;
        $entityList = DB::select($querys);

        if(isset($primaryId) && !isset($multipleRow)){
            $array1d = $entityList[0];
            return $array1d;
        }
        return $entityList;
    }

    public function store(Request $request,$id=null)
    {

        $params = [];
        DB::beginTransaction();
        try {
            $p_id = $id ? $id : '';
            $statusCode = sprintf("%4000s", "");
            $statusMessage = sprintf('%4000s', '');

            $attachedFile = $attachedFileName = $attachedFileType = $attachedFileContent ='';
            $attachedFile = $request->file('fuel_voucher');
            if(isset($attachedFile)){
                $attachedFileName = $attachedFile->getClientOriginalName();
                $attachedFileType = $attachedFile->getMimeType();
                $attachedFileContent = base64_encode(file_get_contents($attachedFile->getRealPath()));
            }else{
                if($id) { // only at file update time when attachment not selected newly, wanted to re-allocate previously inserted
                    $attachedFileData    =  $this->findInsertedData("MEA.VM_FUEL_PKG.get_fuel_consume",$id);
                    $attachedFileName    =  $attachedFileData->fuel_voucher_doc_name?$attachedFileData->fuel_voucher_doc_name:'';
                    $attachedFileType    =  $attachedFileData->fuel_voucher_doc_type?$attachedFileData->fuel_voucher_doc_type:'';
                    $attachedFileContent =  $attachedFileData->fuel_voucher?$attachedFileData->fuel_voucher:'';
                }
            }
            $params = [
                 "p_FUEL_CONSUM_ID" => [
                    "value" => &$p_id,
                    "type" => \PDO::PARAM_INPUT_OUTPUT,
                    "length" => 255
                 ],
                 //"p_VEHICLE_REG_NO" => $request->get("vehicle_id"),
                 "p_DRIVER_ID" => $request->get("driver_id"),
                 "p_VEHICLE_ID" => $request->get("vehicle_id"),
                 "p_REFUELING_DATE" => (($request->get("refueling_date")) ? date("Y-m-d h:i:s", strtotime($request->get("refueling_date")))  : ''),
//               //"p_LAST_REFUELING_DATE" => '',//(($request->get("last_refueling_date")) ? date("Y-m-d", strtotime($request->get("job_date"))) : ''),
                 "p_MILEAGE_ON_REFUELING" => $request->get("mileage_on_refueling"),
                 //"p_LAST_REFUELING_MILEAGE" => '',//$request->get("job_no"),
                 "p_FUEL_QTY"        => $request->get("fuel_qty"),
                 "p_FUEL_UNIT_PRICE" => $request->get("fuel_unit_price"),
                 "p_TOTAL_FUEL_AMOUNT" => $request->get("total_fuel_amount"),
                 "p_REMARKS"         => $request->get("remarks"),
                 "p_FUEL_VOUCHER_NO" => $request->get("fuel_voucher_no"),
                 "p_FUEL_VOUCHER"    =>[
                                    'value' => $attachedFileContent,
                                    'type'  => \PDO::PARAM_LOB,
                                    ],
                 "p_FUEL_TYPE_ID"    => $request->get("fuel_type_id"),
                 "p_CPA_DEPOT_YN"    => $request->get("depot_type"),
                 "p_DEPOT_NAME"      => $request->get("depot_name"),
                 "p_DEPOT_ADDRESS"   => $request->get("depot_address"),
                 "p_EMPLOYEE_ID"     => $request->get("driver_id"),
                 "p_FUEL_VOUCHER_DOC_NAME" => $attachedFileName,
                 "p_FUEL_VOUCHER_DOC_TYPE" => $attachedFileType,
                 "p_FUEL_CONSUMPTION_TYPE_ID" => $request->get("fuel_consumption_type_id"),
                 "p_INSERT_BY"       =>  Auth()->ID(),
                 "o_status_code"     =>  &$statusCode,
                 "o_status_message"  =>  &$statusMessage
            ];

            DB::executeProcedure('MEA.VM_FUEL_PKG.fuel_consump_entry', $params);
            /*echo '<pre>';
            print_r($params);
            die();*/
            if ($id) {
                DB::commit();
                return $params;
            } else {
                DB::commit();
                $flashMessageContent = $this->flashMessageManager->getMessage($params);
                return redirect()->back()->with($flashMessageContent['class'], $flashMessageContent['message']);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return ["exception" => true, "o_status_code" => false, "o_status_message" => $e->getMessage()];
        }
    }

    public function edit($id)
    {
        if($id){
            $insertedData          = $this->findInsertedData("MEA.VM_FUEL_PKG.get_fuel_consume",$id);

        }

        $data =[
            'get_vehicle_reg_no_list' => $this->commonDropDownLookupsList('MEA.vm_VEHICLE_pkg.get',$insertedData->vehicle_id),
            'get_driver_reg_list' => $this->commonDropDownLookupsList('VM_DRIVER_PKG.get_driver_list',$insertedData->driver_id),
            //'loadDecisionDropdown' => $this->loadDecisionDropdown($insertedData->cpa_depot_yn),
            'loadDepotTypeDropdown' => $this->loadDepotTypeDropdown($insertedData->cpa_depot_yn),
            'get_fuel_types' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_fuel_types',$insertedData->fuel_type_id),
            'get_fuel_consumption_types' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_fuel_consumption_types',$insertedData->fuel_consumption_type_id),

            'get_work_type_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_work_type',1),
            'get_fuel_unit_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_fuel_unit',1),
            'get_refuel_frequency_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_refuel_frequency',1),

            'insertedData'            => $insertedData,
        ];

        return view('mea.vms.fuelconsumption.index',compact('data'));
    }

    public function update(Request $request, $id)
    {
        $params = $this->store($request, $id);
        if(isset($params['exception']) && ($params['exception'] == true)) {
            return $params;
        }
        $flashMessageContent = $this->flashMessageManager->getMessage($params);
        return redirect()->route('fuel-consumption-index')->with($flashMessageContent['class'], $flashMessageContent['message']);
    }

    public function datatableList()
    {
        $id = null;
        $querys = "select MEA.VM_FUEL_PKG.get_fuel_consume('".$id."') from dual" ;
        $entityList = DB::select($querys);
        return datatables()->of($entityList)

            ->addColumn('action', function ($query) {
                return '<a href="' . route('fuel-consumption-edit', $query->fuel_consum_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })
            ->make(true);
    }

}
