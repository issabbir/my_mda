<?php

namespace App\Http\Controllers\Mea\Vms;

use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleAssignController extends Controller
{
    private $flashMessageManager;

    public function __construct(FlashMessageManager $flashMessageManager)
    {
        $this->flashMessageManager = $flashMessageManager;
    }
    public function index()
    {
        /*$data = $this->datatableList();
        print_r($data);
        die();*/
        $data = [
            //'get_vehicle_type' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_vehicle_type'),
            //'get_vehicle_class' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_vehicle_class'),
            //'get_vehicle_status' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_vehicle_status'),
            'get_vehicle_reg_no_list' => $this->commonDropDownLookupsList('MEA.VM_DRIVER_ASSIGN_PKG.GET_UNASSIGN_VEHICLE'),

            'loadDecisionDropdown' => $this->loadDecisionDropdown('Y'),
            //'get_driver_type' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_driver_type'),
            'get_assignment_type' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_assignment_type'),
            'get_driver_reg_list' => $this->commonDropDownLookupsList('MEA.VM_DRIVER_ASSIGN_PKG.GET_UNASSIGN_DRIVER'),

            'get_schedule_list' => $this->commonDropDownLookupsList('MEA.VM_SETUP_ENTRY_PKG.GET_SCHEDULE_LIST'),
//            'get_work_type_list' => $this->commonDropDownLookupsList('MEA.VM_DRIVER_ASSIGN_PKG.GET_WORK_TYPE'),

        ];
        return view('mea.vms.vehicleassign.index', compact('data'));
    }

    public function commonDropDownLookupsList($look_up_name = null,$column_selected = null,$fetch_single_colid = null){
        if($fetch_single_colid){
            $query = "Select ".$look_up_name."('".$fetch_single_colid."') from dual" ;
        }else{
            $query = "Select ".$look_up_name."() from dual" ;
        }

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

    public function datatableList()
    {
        $id = null;
        $querys = "SELECT MEA.VM_DRIVER_ASSIGN_PKG.get_driver_assign('".$id."') from dual" ;
        $entityList = DB::select($querys);
        return datatables()->of($entityList)
        ->addColumn('active_yn', function ($query) {
               if($query->active_yn == 'Y'){
                   return 'Active';
               }else{
                   return 'In-Active';
               }

            })

            ->addColumn('schedule_yn', function ($query) {
                if($query->schedule_yn == 'Y'){
                    return 'Yes';
                }else{
                    return 'No';
                }
            })

            ->addColumn('start_date', function($query) {
                return Carbon::parse($query->start_date)->format('d-m-Y');
            })
            ->addColumn('end_date', function($query) {
                return Carbon::parse($query->end_date)->format('d-m-Y');
            })

            ->addColumn('action', function ($query) {
                return '<a href="' . route('vehicle-assign-edit', $query->assignment_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })
            ->make(true);
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
                "p_ASSIGNMENT_ID" => [
                    "value" => &$p_id,
                    "type" => \PDO::PARAM_INPUT_OUTPUT,
                    "length" => 255
                ],
                "p_VEHICLE_ID"          =>  $request->get("vehicle_reg_no"),
                "p_DRIVER_ID"           =>  $request->get("driver_id"),
                "p_SCHEDULE_YN"         => $request->get("schedule_yn"),
                "p_SCHEDULE_ID"         => (($request->get("schedule_id")) ? $request->get("schedule_id") : ''),
                "p_WORK_TYPE_ID"        =>  $request->get("work_type_id"),
                "p_USED_EMPLOYEE_ID"    =>  (($request->get("used_employee_id")) ? $request->get("used_employee_id") : ''),
                "p_START_DATE"          =>  (($request->get("start_date")) ? date("Y-m-d", strtotime($request->get("start_date"))) : ''),
                "p_END_DATE"            =>  (($request->get("end_date")) ? date("Y-m-d", strtotime($request->get("end_date"))) : ''),
                "p_NOTE"                =>  $request->get("remarks"),
                "p_ASSIGNMENT_TYPE_ID"  =>  $request->get("assignment_type_id"),
                "p_ACTIVE_YN"           =>  $request->get("active_yn"),
                "p_INSERT_BY"           =>  Auth()->ID(),
                "o_status_code"         =>  &$statusCode,
                "o_status_message"      =>  &$statusMessage
            ];

            DB::executeProcedure('MEA.VM_DRIVER_ASSIGN_PKG.driver_assign_entry', $params);

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
            $insertedData   = $this->findInsertedData("MEA.VM_DRIVER_ASSIGN_PKG.get_driver_assign",$id);
        }

        $data =[
            //'get_vehicle_type' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_vehicle_type',$insertedData->vehicle_type_id),
            //'get_vehicle_class' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_vehicle_class',$insertedData->vehicle_class_id),
            //'get_vehicle_status' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_vehicle_status',$insertedData->vehicle_status_id),
            'get_vehicle_reg_no_list' => $this->commonDropDownLookupsList('MEA.VM_DRIVER_ASSIGN_PKG.get_vehicle_for_upd',$insertedData->vehicle_id,$insertedData->vehicle_id),

            'loadDecisionDropdown'  => $this->loadDecisionDropdown($insertedData->active_yn),
            //'get_driver_type' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_driver_type',$insertedData->driver_type_id),
            'get_assignment_type' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_assignment_type',$insertedData->assignment_type_id),
            'get_driver_reg_list' => $this->commonDropDownLookupsList('MEA.VM_DRIVER_ASSIGN_PKG.get_driver_for_upd',$insertedData->driver_id,$insertedData->driver_id),
            'get_schedule_list' => $this->commonDropDownLookupsList('MEA.VM_SETUP_ENTRY_PKG.GET_SCHEDULE_LIST', $insertedData->schedule_id),
            'insertedData'          => $insertedData,
            ];

        return view('mea.vms.vehicleassign.index',compact('data'));
    }

    public function update(Request $request, $id)
    {
        $params = $this->store($request, $id);
        if(isset($params['exception']) && ($params['exception'] == true)) {
            return $params;
        }
        $flashMessageContent = $this->flashMessageManager->getMessage($params);
        return redirect()->route('vehicle-assign-index')->with($flashMessageContent['class'], $flashMessageContent['message']);
    }

}
