<?php

namespace App\Http\Controllers\Mea\Vms;

use App\Contracts\Mea\Vms\CommonContract;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SupplierController extends Controller
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
             'loadDecisionDropdown' => $this->commonVmsManager->loadDecisionDropdown('Y'),
        ];
        return view('mea.vms.supplier.index', compact('data'));
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
                "p_V_SUPPLIER_ID" => [
                    "value" => &$p_id,
                    "type" => \PDO::PARAM_INPUT_OUTPUT,
                    "length" => 255
                ],
                "p_V_SUPPLIER_NAME" => $request->get("v_supplier_name"),
                "p_V_SUPPLIER_NAME_BN"    => $request->get("v_supplier_name_bn"),
                "p_V_SUPPLIER_ADDRESS"        => $request->get("v_supplier_address"),
                "p_V_SUPPLIER_CONTACT_NO"    => $request->get("v_supplier_contact_no"),
                "p_SUPPLIER_TENDER_REFF"    => $request->get("supplier_tender_reff"),
                "p_ACTIVE_YN"          => $request->get("active_yn"),
                "p_CONTACT_START_DT"    => (($request->get("contact_start_dt")) ? date("Y-m-d", strtotime($request->get("contact_start_dt")))  : ''),
                "p_CONTACT_PERSON_NAME"    => $request->get("contact_person_name"),
                "p_CONTACT_PERSON_MOBILE"    => $request->get("contact_person_mobile"),
                "p_INSERT_BY"          =>  Auth()->ID(),
                "p_CONTACT_EXPIRY_DT"    => (($request->get("contact_expiry_dt")) ? date("Y-m-d", strtotime($request->get("contact_expiry_dt")))  : ''),
                "o_status_code"        =>  &$statusCode,
                "o_status_message"     =>  &$statusMessage
            ];

            DB::executeProcedure('MEA.VM_SETUP_ENTRY_PKG.supplier_entry', $params);

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
                            $attachedFileData    = $this->commonVmsManager->findInsertedData("MEA.VM_DOCUMENT_PKG.get_document",$doc_master_id[$key]);
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
                        "p_DOC_TYPE_ID" => 3,
                        "p_DRIVER_ID" => '',
                        "p_VEHICLE_ID" => '',
                        "p_DOC_FILE_SIZE" => '',
                        "p_INSERT_BY" => Auth()->ID(),
                        "p_V_SUPPLIER_ID" => $params['p_V_SUPPLIER_ID']['value'],
                        "o_status_code" => &$statusCode,
                        "o_status_message" => &$statusMessage
                    ];

                    DB::executeProcedure('MEA.VM_DOCUMENT_PKG.doc_master_entry', $params2);

                }

            }

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


    private function findInsertedDataArrParam($pkgFunction,$v_supplier_id = null,$doc_type_id = null,$multipleRow=null){
        $querys = "SELECT ".$pkgFunction."('".$v_supplier_id."','".$doc_type_id."') from dual" ;
        $entityList = DB::select($querys);

        if(isset($v_supplier_id) && !isset($multipleRow)){
            $array1d = $entityList[0];
            return $array1d;
        }
        return $entityList;
    }

    public function edit($id)
    {

        if($id){
            $insertedData   = $this->commonVmsManager->findInsertedData("MEA.VM_SETUP_ENTRY_PKG.get_supplier_record",$id);
            $insertedDocsData       = $this->findInsertedDataArrParam("MEA.VM_DOCUMENT_PKG.get_v_supplier_document",$id,3,'Y');
        }

        $data =[
            // 'loadDecisionDropdown'    => $this->commonVmsManager->loadDecisionDropdown($insertedData->active_yn),
            'insertedData'            => $insertedData,
            'insertedDocsData'   => $insertedDocsData,
        ];

        return view('mea.vms.supplier.index',compact('data'));
    }

    public function update(Request $request, $id)
    {
        $params = $this->store($request, $id);
        if(isset($params['exception']) && ($params['exception'] == true)) {
            return $params;
        }
        $flashMessageContent = $this->flashMessageManager->getMessage($params);
        return redirect()->route('supplier-index')->with($flashMessageContent['class'], $flashMessageContent['message']);
    }

    public function datatableList()
    {
        $id = null;

        $querys = "select MEA.VM_SETUP_ENTRY_PKG.get_supplier_record('".$id."') from dual" ;
        $entityList = DB::select($querys);
        return datatables()->of($entityList)
        ->addColumn('contact_start_dt', function($query) {
                   return Carbon::parse($query->contact_start_dt)->format('d-m-Y');
               })

            ->addColumn('active_yn', function ($query) {
               if($query->active_yn == 'Y'){
                   return 'Active';
               }else{
                   return 'In-Active';
               }

            })
            ->addColumn('action', function ($query) {
                $baseUrl = request()->root();
                return '<a href="' . route('supplier-edit', $query->v_supplier_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })->make(true);
        //| <a target="_blank" href="'.$baseUrl.'/report/render/maintanance_details?xdo=/~weblogic/VMS/RPT_VEHICLE_MAINTENANCE.xdo&p_maintanance_id='.$query->workshop_id.'&type=pdf&filename=maintanance_details"  ><i class="bx bx-download cursor-pointer"></i></a>
    }

    public function deleteSupplierDocs(Request $request, $id){

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
