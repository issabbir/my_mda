<?php

namespace App\Http\Controllers\Mea\Vms;

use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverEnlistedController extends Controller
{
    private $flashMessageManager;

    public function __construct(FlashMessageManager $flashMessageManager)
    {
        $this->flashMessageManager = $flashMessageManager;
    }

    public function index()
    {
        $data = [
            'loadDecisionDropdown' => $this->loadDecisionDropdown('Y'),
            'get_license_status' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_license_status','1'),
            'get_driver_type' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_driver_type'),
            'get_gender_type' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_gender_type'),
            'get_marital_status' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_marital_status'),
            'loadPresentDivision' => $this->loadDivision(),
            'loadPermanentDivision' => $this->loadDivision(),
        ];
        return view('mea.vms.driver.index', compact('data'));
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

    public function loadDivision($column_selected = null){
        $query = "Select division_id,division_name from v_division " ;
        $entityList = DB::select($query);
        $entityOption = [];
        $entityOption[] = "<option value=''>Please select an option</option>";
        foreach ($entityList as $item) {
            $entityOption[] = "<option value='".$item->division_id."'".($column_selected == $item->division_id ? 'selected':'').">".$item->division_name."</option>";
        }
        return $entityOption;
    }

    public function loadDistrict($column_selected = null,$condition =null){
        $query = "Select district_id,district_name from v_district ".(isset($condition)? " Where ".$condition : ''); //.(isset($condition)? " Where ".$condition : '') ;
        $entityList = DB::select($query);
        $entityOption = [];
        $entityOption[] = "<option value=''>Please select an option</option>";
        foreach ($entityList as $item) {
            $entityOption[] = "<option value='".$item->district_id."'".($column_selected == $item->district_id ? 'selected':'').">".$item->district_name."</option>";
        }
        return $entityOption;
    }

    public function loadThana($column_selected = null,$condition =null){
        $query = "Select thana_id,thana_name from v_thana ".(isset($condition)? " Where ".$condition : '');
        $entityList = DB::select($query);
        $entityOption = [];
        $entityOption[] = "<option value=''>Please select an option</option>";
        foreach ($entityList as $item) {
            $entityOption[] = "<option value='".$item->thana_id."'".($column_selected == $item->thana_id ? 'selected':'').">".$item->thana_name."</option>";
        }
        return $entityOption;
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


            if($request->get("driver_type_id") == '1'){
                $driverCpaEmpId = $request->get("driver_emp_id_as_cpa_no");
                $driverCpaNo = $request->get("driver_emp_code_as_cpa_no");
            }else{
                $driverCpaEmpId = '';
                $driverCpaNo = $request->get("driver_cpa_no");
                $driverCpaNo = (isset($driverCpaNo)? $driverCpaNo:'');
            }

            $attachedFile = $attachedFileName = $attachedFileType = $attachedFileContent ='';

            $attachedFile = $request->file('driver_photo');
            if(isset($attachedFile)){
                $attachedFileName = $attachedFile->getClientOriginalName();
                $attachedFileType = $attachedFile->getMimeType();
                $attachedFileContent = base64_encode(file_get_contents($attachedFile->getRealPath()));
            }else{
                if($id) { // only at file update time when attachment not selected newly, wanted to re-allocate previously inserted
                    $attachedFileData    =  $this->findInsertedData("MEA.VM_DRIVER_PKG.get_driver_list",$id);
                    $attachedFileName    =  $attachedFileData->photo_doc_name;
                    $attachedFileType    =  $attachedFileData->photo_doc_type;
                    $attachedFileContent =  $attachedFileData->driver_photo;
                }else{

                    if($driverCpaEmpId){
                        // these statements for cpa pmis image only
                        $queryView = "Select emp_photo from v_employee where emp_id = $driverCpaEmpId";
                        $entityViewData = DB::select($queryView);

                        if(isset($entityViewData[0]->emp_photo)){ //if(isset($attachedFileData->emp_photo)){
                            $attachedFileData    =  $entityViewData[0]; // two dimension to single dimension
                            $attachedFileDataArr = explode(';base64,',$attachedFileData->emp_photo); //separate type and encrypted data
                            $attachedFileDataTypeArr = explode('data:',$attachedFileDataArr[0]); // remove flag data: from type

                            //echo $attachedFileName    =  $attachedFileData->photo_doc_name;
                            $attachedFileType    =  $attachedFileDataTypeArr[1];
                            $attachedFileContent =  $attachedFileDataArr[1];
                            //create extension
                            $attachedFileTypeArr = explode('/',$attachedFileType);
                            $attachedFileName    = $driverCpaNo.'.'.$attachedFileTypeArr[1];

                        }else{
                            $attachedFileName    =  '';
                            $attachedFileType    =  '';
                            $attachedFileContent =  '';
                        }

                    }else{
                        $attachedFileName    =  '';
                        $attachedFileType    =  '';
                        $attachedFileContent =  '';
                    }

                }
            }

            $params = [
                "p_DRIVER_ID" => [
                    "value" => &$p_id,
                    "type" => \PDO::PARAM_INPUT_OUTPUT,
                    "length" => 255
                ],
                "p_CPA_EMP_ID"                =>  $driverCpaEmpId? $driverCpaEmpId:'',
                "p_DRIVER_CPA_NO"             =>  $driverCpaNo,
                "p_DRIVER_NAME"               =>  $request->get("driver_name"),
                "p_DRIVER_NAME_BN"            =>  $request->get("driver_name_bn"),
                "p_DRIVER_FATHER_NAME"        =>  $request->get("driver_father_name"),
                "p_DRIVER_MOTHER_NAME"        =>  $request->get("driver_mother_name"),
                "p_DRIVER_SPOUSE_NAME"        =>  $request->get("driver_spouse_name"),
                "p_GENDER_TYPE_ID"            =>  $request->get("gender_type_id"),
                "p_MARITIAL_STATUS_ID"        =>  $request->get("marital_status_id"),
                "p_DOB"                       =>  (($request->get("dob")) ? date("Y-m-d", strtotime($request->get("dob"))) : ''),
                "p_NID_NO"                    =>  $request->get("nid_no"),
                "p_MOBILE_NO"                 =>  $request->get("mobile_no"),
                "p_EMARGENCY_NO"              =>  $request->get("emargency_no"),
                "p_ACTIVE_YN"                 =>  $request->get("active_yn"),
                "p_DL_NO"                     =>  $request->get("dl_no"),
                "p_DL_ISSUE_DATE"             =>  (($request->get("dl_issue_date")) ? date("Y-m-d", strtotime($request->get("dl_issue_date"))) : ''),//$request->get("dl_issue_date"),
                "p_DL_EXPIRY_DATE"            =>  (($request->get("dl_expiry_date")) ? date("Y-m-d", strtotime($request->get("dl_expiry_date"))) : ''),//$request->get("dl_expiry_date"),
                "p_DRIVER_PHOTO"              => [
                                                    'value' => $attachedFileContent,
                                                    'type'  => \PDO::PARAM_LOB,
                                                 ],
                "p_REMARKS"                   =>  $request->get("remarks"),
                "p_LICENSE_STATUS_ID"         =>  $request->get("license_status_id"),
                "p_DRIVER_TYPE_ID"            =>  $request->get("driver_type_id"),
                "p_PHOTO_DOC_NAME"            =>  $attachedFileName,
                "p_PHOTO_DOC_TYPE"            =>  $attachedFileType,
                "p_INSERT_BY"                 =>  Auth()->ID(),
                "o_status_code"               =>  &$statusCode,
                "o_status_message"            =>  &$statusMessage

            ];

            DB::executeProcedure('MEA.VM_DRIVER_PKG.vm_driver_entry', $params);

            if($params['o_status_code'] == 1) {
                $params2 = $params3 = [];
                $address_id1 = isset($id)? ( $request->get('permanent_address_id')? $request->get('permanent_address_id') : ''):'';
                $address_id2 = isset($id)? ( $request->get('present_address_id')? $request->get('present_address_id') : ''):'';
                $statusCode = sprintf("%4000s", "");
                $statusMessage = sprintf('%4000s', '');
                $address_id2 = $address_id2 ? $address_id2 : '';

                $params2 = [
                    "p_ADDRESS_ID" => [
                        "value" => &$address_id2,
                        "type" => \PDO::PARAM_INPUT_OUTPUT,
                        "length" => 255
                    ],
                    "p_ADDRESS_LINE_1" => $request->get("present_address_line1"),
                    "p_ADDRESS_LINE_2" => $request->get("present_address_line2"),
                    "p_THANA_ID"       => $request->get("present_thana_id"),
                    "p_DESCRIPTION"    => '',
                    "p_ADDRESS_TYPE_ID" => '2',//$request->get("driver_type_id"),
                    "p_DRIVER_ID"       => $params['p_DRIVER_ID'],
                    "p_DRIVER_TYPE_ID"  => $request->get("driver_type_id"),
                    "p_INSERT_BY"       => Auth()->ID(),
                    "o_status_code"     => &$statusCode,
                    "o_status_message"  => &$statusMessage
                ];

                DB::executeProcedure('MEA.VM_DRIVER_PKG.driver_address_entry', $params2);

                if($params2['o_status_code'] != 1) {
                    DB::rollback();
                    $flashMessageContent = $this->flashMessageManager->getMessage($params2);
                    return redirect()->back()->with($flashMessageContent['class'], $flashMessageContent['message']);
                }

                $address_id1 = $address_id1 ? $address_id1 : '';
                $statusCode = sprintf("%4000s", "");
                $statusMessage = sprintf('%4000s', '');
                $params3 = [
                    "p_ADDRESS_ID" => [
                        "value" => &$address_id1,
                        "type" => \PDO::PARAM_INPUT_OUTPUT,
                        "length" => 255
                    ],
                    "p_ADDRESS_LINE_1" => $request->get("permanent_address_line1"),
                    "p_ADDRESS_LINE_2" => $request->get("permanent_address_line2"),
                    "p_THANA_ID"       => $request->get("permanent_thana_id"),
                    "p_DESCRIPTION"    => '',
                    "p_ADDRESS_TYPE_ID" => '1',//$request->get("driver_type_id"),
                    "p_DRIVER_ID"       => $params['p_DRIVER_ID'],
                    "p_DRIVER_TYPE_ID"  => $request->get("driver_type_id"),
                    "p_INSERT_BY"       => Auth()->ID(),
                    "o_status_code"     => &$statusCode,
                    "o_status_message"  => &$statusMessage
                ];

                DB::executeProcedure('MEA.VM_DRIVER_PKG.driver_address_entry', $params3);

                if($params3['o_status_code'] != 1) {
                    DB::rollback();
                    $flashMessageContent = $this->flashMessageManager->getMessage($params3);
                    return redirect()->back()->with($flashMessageContent['class'], $flashMessageContent['message']);
                }
               /* echo '<pre>';
                print_r($params);
                print_r($params2);
                print_r($params3);
                print($id);
                die();*/
            }else{
                DB::rollback();
                $flashMessageContent = $this->flashMessageManager->getMessage($params);
                return redirect()->back()->with($flashMessageContent['class'], $flashMessageContent['message']);
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

    public function datatableList()
    {
        $driver_id = null;
        $querys = "SELECT MEA.VM_DRIVER_PKG.get_driver_list('".$driver_id."') from dual" ;
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
            */
            ->addColumn('dl_expiry_date', function($query) {
                return Carbon::parse($query->dl_expiry_date)->format('d-m-Y');
            })

            ->addColumn('action', function ($query) {
                $baseUrl = request()->root();
                return '<a href="'. route('driver-enlist-edit', $query->driver_id) .'"><i class="bx bx-edit cursor-pointer"></i></a>'.
                    '| <a  target="_blank" href="'.$baseUrl.'/report/render/driver_details?xdo=/~weblogic/VMS/RPT_DRIVER_DETAILS.xdo&p_driver_id='.$query->driver_id.'&type=pdf&filename=driver_details"
                ><i class="bx bx-download cursor-pointer"></i></a>';
            })
            ->make(true);

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

    public function edit($id)
    {
        if($id){
            $insertedData                   = $this->findInsertedData("MEA.VM_DRIVER_PKG.get_driver_list",$id);
            $insertedAddressData            = $this->findInsertedData("MEA.VM_DRIVER_PKG.get_driver_address",$id,'y');

            //search the row value where permanent address type id is =1 and present address id = 2
            $permanentkey = array_search('1', array_column($insertedAddressData, 'address_type_id'));
            $presentkey = array_search('2', array_column($insertedAddressData, 'address_type_id'));

            $insertedPermanentAddressData   = $insertedAddressData[$permanentkey];
            $insertedPresentAddressData     = $insertedAddressData[$presentkey];
        }

        $data =[
            'loadDecisionDropdown'  => $this->loadDecisionDropdown($insertedData->active_yn),
            'get_license_status'    => $this->commonDropDownLookupsList('vm_lookup_pkg.get_license_status',$insertedData->license_status_id),
            'get_driver_type'       => $this->commonDropDownLookupsList('vm_lookup_pkg.get_driver_type',$insertedData->driver_type_id),
            'get_gender_type'       => $this->commonDropDownLookupsList('vm_lookup_pkg.get_gender_type',$insertedData->gender_type_id),
            'get_marital_status'    => $this->commonDropDownLookupsList('vm_lookup_pkg.get_marital_status',$insertedData->maritial_status_id),
            'loadPresentDivision'   => $this->loadDivision($insertedPresentAddressData->division_id),
            'loadPermanentDivision' => $this->loadDivision($insertedPermanentAddressData->division_id),
            'loadPresentDistricts'  => $this->loadDistrict($insertedPresentAddressData->district_id,'division_id = '.$insertedPresentAddressData->division_id),
            'loadPermanentDistricts'=> $this->loadDistrict($insertedPermanentAddressData->district_id,'division_id = '.$insertedPermanentAddressData->division_id),
            'loadPresentThanas'     => $this->loadThana($insertedPresentAddressData->thana_id, 'district_id = '.$insertedPresentAddressData->district_id),
            'loadPermanentThanas'   => $this->loadThana($insertedPermanentAddressData->thana_id, 'district_id = '.$insertedPermanentAddressData->district_id),
            'insertedPermAddData'   => $insertedPermanentAddressData,
            'insertedPresAddData'   => $insertedPresentAddressData,
            'insertedData'          => $insertedData,
        ];

        return view('mea.vms.driver.index',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $params = $this->store($request, $id);
        if(isset($params['exception']) && ($params['exception'] == true)) {
            return $params;
        }
        $flashMessageContent = $this->flashMessageManager->getMessage($params);
        return redirect()->route('driver-enlist-index')->with($flashMessageContent['class'], $flashMessageContent['message']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
