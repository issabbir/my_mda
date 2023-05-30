<?php

namespace App\Http\Controllers\Mea\Vms;

use App\Contracts\Mea\Vms\CommonContract;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleInfoController extends Controller
{
    private $flashMessageManager;
    private $commonVmsManager;

    public function __construct(CommonContract $commonVmsManager, FlashMessageManager $flashMessageManager)
    {
        $this->commonVmsManager    = $commonVmsManager;
        $this->flashMessageManager = $flashMessageManager;
    }
    public function index()
    {
        $data = [
            'get_vehicle_type' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_vehicle_type'),
            'get_vehicle_class' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_vehicle_class'),
            'get_color' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_color'),
            'get_manufacturer' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_manufacturer'),
            'get_country' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_country'),
            'get_tracker_device' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_tracker_device'),
            'get_vehicle_supplier' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_vehicle_supplier'),
            'get_fuel_types' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_fuel_types'),
            'get_vehicle_status' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_vehicle_status','1'),
            'loadDecisionDropdown' => $this->commonVmsManager->loadDecisionDropdown('Y'),
            'loadDecisionActiveStatusDropdown' => $this->commonVmsManager->loadDecisionDropdown('Y'),
            'get_engine_type_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_engine_type'),
        ];
        return view('mea.vms.vehicleinfo.index', compact('data'));
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

    public function store(Request $request,$id=null)
    {
        $params = [];
        DB::beginTransaction();
        try {
                $p_id = $id ? $id : '';
                $statusCode = sprintf("%4000s", "");
                $statusMessage = sprintf('%4000s', '');

                $params = [
                      "p_VEHICLE_ID" => [
                            "value" => &$p_id,
                            "type" => \PDO::PARAM_INPUT_OUTPUT,
                            "length" => 255
                      ],
                      "p_VEHICLE_REG_NO"            =>  $request->get("vehicleRegNo"),
                      "p_CHASSIS_NO"                =>  $request->get("chassisNo"),
                      "p_ENGINE_NO"                 =>  $request->get("engineNo"),
                      "p_CC"                        =>  $request->get("cc"),
                      "p_MANUFACTUR_YEAR"           =>  $request->get("manufacturYear"),
                      "p_NO_OF_SEATS"               =>  $request->get("no_of_seats"),
                      "p_MODEL_NAME"                =>  $request->get("modelName"),
                      "p_UNLADEN_WEIGHT"            =>  $request->get("unladenWeight"),
                      "p_LADEN_WEIGHT"              =>  $request->get("ladenWeight"),
                      "p_REG_YEAR"                  =>  (($request->get("regYear")) ? date("Y-m-d", strtotime($request->get("regYear"))) : ''),
                      "p_PURCHASE_DATE"             =>  (($request->get("purchaseDate")) ? date("Y-m-d", strtotime($request->get("purchaseDate"))) : ''),
                      "p_CPA_VEHICLE_YN"            =>  $request->get("cpaVehicleYn"),
                      "p_INITIAL_MILEAGE"           =>  $request->get("initialMileage"),
                      "p_CURRENT_MILEAGE"           =>  $request->get("currentMileage"),
                      "p_PURCHASE_PRICE"            =>  $request->get("purchasePrice"),
                      "p_MAINTANANCE_COST"          =>  $request->get("maintananceCost"),
                      "p_VEHICLE_NAME"              =>  $request->get("vehicleName"),
                      "p_VEHICLE_NAME_BN"           =>  $request->get("vehicleNameBn"),
                      "p_VEHICLE_CPA_NO"            =>  $request->get("VehicleCpaNo"),
                      "p_TAX_TOKEN_ISSUE_DATE"      =>  (($request->get("taxTokenIssueDate")) ? date("Y-m-d", strtotime($request->get("taxTokenIssueDate"))) : ''),
                      "p_TAX_TOKEN_EXPIRY_DATE"     =>  (($request->get("taxTokenExpiryDate")) ? date("Y-m-d", strtotime($request->get("taxTokenExpiryDate"))) : ''),
                      "p_FITNESS_ISSUE_DATE"        =>  (($request->get("fitnessIssueDate")) ? date("Y-m-d", strtotime($request->get("fitnessIssueDate"))) : ''),
                      "p_FITNESS_EXPIRY_DATE"       =>  (($request->get("fitnessExpiryDate")) ? date("Y-m-d", strtotime($request->get("fitnessExpiryDate"))) : ''),
                      "p_ROUTE_PERMIT_ISSUE_DATE"   =>  (($request->get("routePermitIssueDate")) ? date("Y-m-d", strtotime($request->get("routePermitIssueDate"))) : ''),
                      "p_ROUTE_PERMIT_EXPIRY_DATE"  =>  (($request->get("routePermitExpiryDate")) ? date("Y-m-d", strtotime($request->get("routePermitExpiryDate"))) : ''),
                      /*"p_VEHICLE_OWNER_NAME"        =>  $request->get("vehicleOwnerName"),
                      "p_VEHICLE_OWNER_NAME_BN"     =>  $request->get("vehicleOwnerNameBn"),
                      "p_VEHICLE_OWNER_NID"         =>  $request->get("vehicleOwnerNid"),
                      "p_VEHICLE_OWNER_MOBILE_NO"   =>  $request->get("vehicleOwnerMobileNo"),*/

                      "p_VEHICLE_STATUS_ID"         =>  $request->get("vehicleStatusId"),
                      "p_COLOR_ID"                  =>  $request->get("colorId"),
                      "p_VEHICLE_CLASS_ID"          =>  $request->get("vehicleClassId"),
                      "p_VEHICLE_TYPE_ID"           =>  $request->get("vehicleTypeId"),
                      "p_MANUFRACTURER_ID"          =>  $request->get("manufacturerId"),
                      "p_FUEL_TYPE_ID"              =>  $request->get("fuelTypeId"),
                      "p_V_SUPPLIER_ID"             =>  $request->get("vehicleSupplierId"),
                      "p_AC_YN"                     =>  $request->get("acYn"),
                      "p_MAKERS_COUNTRY_ID"         =>  $request->get("makersCountryId"),
                      "p_TRACKER_ID"                =>  $request->get("trackerId"),
                      "p_ACTIVE_YN"                 =>  $request->get("active_yn"),
                      "p_ASSIGNED_EMP_ID"           =>  $request->get("assigned_emp_id"),
                      "p_ENGINE_TYPE_ID"            =>  $request->get("engineTypeId"),
                      "p_VEHICLE_VIP_YN"            =>  $request->get("vehicle_vip_yn"),

                      "p_INSERT_BY"                 =>  Auth()->ID(),
                      "o_status_code"               =>  &$statusCode,
                      "o_status_message"            =>  &$statusMessage

                ];

                DB::executeProcedure('MEA.VM_VEHICLE_PKG.vm_vehicle_entry', $params);

            $doc_file_name  = ($request->get('doc_file_name'))? $request->get('doc_file_name'): array();
            $doc_master_id  = ($request->get('doc_master_id'))? $request->get('doc_master_id'): array();
            $doc_file       = ($request->file('doc_file'))? $request->file('doc_file'): array();

            if($params['o_status_code'] == 1) {

                foreach ($doc_file_name as $key => $value) {
                    $attachedFile = $attachedFileName = $attachedFileType = $attachedFileContent ='';

                    if(isset($doc_file[$key])){
                        $attachedFile = $doc_file[$key];
                        $attachedFileName = $attachedFile->getClientOriginalName();
                        $attachedFileType = $attachedFile->getMimeType();
                        $attachedFileContent = base64_encode(file_get_contents($attachedFile->getRealPath()));
                        $docFileInputtedName = $doc_file_name[$key];
                    }else{
                        if($id) { // only at file update time when attachment not selected newly, wanted to re-allocate previously inserted
                          $attachedFileData    = $this->findInsertedData("MEA.VM_DOCUMENT_PKG.get_document",$doc_master_id[$key]);
                          $attachedFileName    =  $attachedFileData->doc_file_path;
                          $attachedFileType    =  $attachedFileData->doc_type;
                          $attachedFileContent =  $attachedFileData->doc_file;
                          $docFileInputtedName =  isset($doc_file_name[$key])? $doc_file_name[$key] : $attachedFileData->doc_file_name;
                        }
                    }

                    $params2 = [];
                    $pk_id = isset($id) ? (isset($doc_master_id[$key]) ? $doc_master_id[$key] : '') : '';
                    $statusCode = sprintf("%4000s", "");
                    $statusMessage = sprintf('%4000s', '');

                    $params2 = [
                        "p_DOC_MASTER_ID" => [
                            "value" => &$pk_id,
                            "type" => \PDO::PARAM_INPUT_OUTPUT,
                            "length" => 255
                        ],
                        "p_DOC_FILE_NAME" => isset($docFileInputtedName)? $docFileInputtedName : '',
                        "p_DOC_FILE" => [
                            'value' => $attachedFileContent,
                            'type'  => \PDO::PARAM_LOB,
                        ],
                        "p_DOC_TYPE" => $attachedFileType,
                        "p_DOC_FILE_SYSTEM_YN" => 'N',
                        "p_DOC_FILE_PATH" => $attachedFileName,
                        "p_DOC_TYPE_ID" => 1,
                        "p_DRIVER_ID" => '',
                        "p_VEHICLE_ID" => $params['p_VEHICLE_ID']['value'],
                        "p_DOC_FILE_SIZE" => '',
                        "p_INSERT_BY" => Auth()->ID(),
                        "p_V_SUPPLIER_ID" => '',
                        "o_status_code" => &$statusCode,
                        "o_status_message" => &$statusMessage
                    ];

                    DB::executeProcedure('MEA.VM_DOCUMENT_PKG.doc_master_entry', $params2);

                }

            }
         /*   echo '<pre>';
            print_r($params);
            print_r($params2);
die();die();*/
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

    public function update(Request $request, $id)
    {
        $params = $this->store($request, $id);

        if(isset($params['exception']) && ($params['exception'] == true)) {
            return $params;
        }
        $flashMessageContent = $this->flashMessageManager->getMessage($params);
        return redirect()->route('vehicle-info-index')->with($flashMessageContent['class'], $flashMessageContent['message']);
    }
    private function findInsertedData($pkgFunction,$vehicle_id = null){
        //$querys = "SELECT MEA.vm_VEHICLE_pkg.get('".$vehicle_id."') from dual" ;
        $querys = "SELECT ".$pkgFunction."('".$vehicle_id."') from dual" ;
        $entityList = DB::select($querys);

        if($vehicle_id){
            $array1d = $entityList[0];
            return $array1d;
        }
        return $entityList;
    }

    private function findInsertedDataArrParam($pkgFunction,$vehicle_id = null,$doc_type_id = null,$multipleRow=null){
        //$querys = "SELECT MEA.vm_VEHICLE_pkg.get('".$vehicle_id."') from dual" ;
        $querys = "SELECT ".$pkgFunction."('".$vehicle_id."','".$doc_type_id."') from dual" ;
        $entityList = DB::select($querys);

        if(isset($vehicle_id) && !isset($multipleRow)){
            $array1d = $entityList[0];
            return $array1d;
        }
        return $entityList;
    }

    public function edit($id)
    {
        if($id){
            $insertedData           = $this->findInsertedData("MEA.vm_VEHICLE_pkg.get",$id);
            $insertedDocsData       = $this->findInsertedDataArrParam("MEA.VM_DOCUMENT_PKG.get_vehicle_document",$id,1,'Y');
        }

        $data =[
            'get_vehicle_type'      => $this->commonDropDownLookupsList('vm_lookup_pkg.get_vehicle_type',$insertedData->vehicle_type_id),
            'get_vehicle_class'     => $this->commonDropDownLookupsList('vm_lookup_pkg.get_vehicle_class',$insertedData->vehicle_class_id),
            'get_color'             => $this->commonDropDownLookupsList('vm_lookup_pkg.get_color',$insertedData->color_id),
            'get_manufacturer'      => $this->commonDropDownLookupsList('vm_lookup_pkg.get_manufacturer',$insertedData->manufracturer_id),
            'get_country'           => $this->commonDropDownLookupsList('vm_lookup_pkg.get_country',$insertedData->makers_country_id),
            'get_tracker_device'    => $this->commonVmsManager->commonDropDownLookupsList('VM_VEHICLE_PKG.get_tracker_rec_upd',$insertedData->tracker_id,$insertedData->tracker_id),
            'get_vehicle_supplier'  => $this->commonDropDownLookupsList('vm_lookup_pkg.get_vehicle_supplier',$insertedData->v_supplier_id),
            'get_fuel_types'        => $this->commonDropDownLookupsList('vm_lookup_pkg.get_fuel_types',$insertedData->fuel_type_id),
            'get_vehicle_status'    => $this->commonDropDownLookupsList('vm_lookup_pkg.get_vehicle_status',$insertedData->vehicle_status_id),
            'loadDecisionDropdown'  => $this->commonVmsManager->loadDecisionDropdown($insertedData->cpa_vehicle_yn),
            'loadDecisionActiveStatusDropdown' => $this->commonVmsManager->loadDecisionDropdown($insertedData->active_yn),
            'get_engine_type_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_engine_type',$insertedData->engine_type_id),
            'insertedData'       => $insertedData,
            'insertedDocsData'   => $insertedDocsData,
        ];
        return view('mea.vms.vehicleinfo.index',compact('data'));
    }

    public function datatableList()
    {
        $vehicle_id = null;
        $querys = "SELECT MEA.vm_VEHICLE_pkg.get('".$vehicle_id."') from dual" ;
        $entityList = DB::select($querys);
        return datatables()->of($entityList)
        ->addColumn('active_yn', function ($query) {
               if($query->active_yn == 'Y'){
                   return 'Active';
               }else{
                   return 'In-Active';
               }

            })
            /*
            ->addColumn('start_date', function($query) {
                return Carbon::parse($query->start_date)->format('Y-m-d');
            })
            ->addColumn('end_date', function($query) {
                return Carbon::parse($query->end_date)->format('Y-m-d');
            })
            */
            ->addColumn('action', function ($query) {
                $baseUrl = request()->root();
                return '<a href="' . route('vehicle-info-edit', $query->vehicle_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>
                | <a  target="_blank" href="'.$baseUrl.'/report/render/vehicle_details?xdo=/~weblogic/VMS/RPT_VEHICLES_DETAIL.xdo&p_vehicle_id='.$query->vehicle_id.'&type=pdf&filename=vehicle_details"
                ><i class="bx bx-download cursor-pointer"></i></a>';
            })
            ->make(true);
    }


    public function deleteDocs(Request $request, $id){

        if($id){
            DB::beginTransaction();

            try {
                $params = [];
                $statusCode = sprintf("%4000s", "");
                $statusMessage = sprintf('%4000s', '');
                $params = [
                    "p_DOC_MASTER_ID" => [
                        "value" => &$id,
                        "type" => \PDO::PARAM_INPUT_OUTPUT,
                        "length" => 255
                    ],
                    "o_status_code"    => &$statusCode,
                    "o_status_message" => &$statusMessage
                ];

                DB::executeProcedure('MEA.VM_DOCUMENT_PKG.doc_maste_del', $params);
                DB::commit();
                return response()->json($params);

            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(["exception" => true, "o_status_code" => false, "o_status_message" => $e->getMessage()]);
            }

        }
        return response()->json(["exception" => true, "o_status_code" => false, "o_status_message" => 'Nothing Found']);
    }


}
