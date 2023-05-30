<?php

namespace App\Http\Controllers\Mea\Vms;

use App\Contracts\Mea\Vms\CommonContract;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkshopController extends Controller
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
            'get_department' => $this->commonVmsManager->commonDropDownLookupsList('vm_lookup_pkg.get_department',18,18),
            'get_workshop_type' => $this->commonVmsManager->commonDropDownLookupsList('vm_lookup_pkg.get_workshop_type'),
//            'get_location' => $this->commonVmsManager->commonDropDownLookupsList('vm_lookup_pkg.get_location',1),
            'loadDecisionDropdown' => $this->commonVmsManager->loadDecisionDropdown('Y'),
        ];
        return view('mea.vms.workshop.index', compact('data'));
    }

    public function store(Request $request,$id=null)
    {
        /* echo '<pre>';
         print_r($request->all());
         die();*/
        $params = [];
        DB::beginTransaction();
        try {
            $p_id = $id ? $id : '';
            $statusCode = sprintf("%4000s", "");
            $statusMessage = sprintf('%4000s', '');

            $params = [
                "p_WORKSHOP_ID" => [
                    "value" => &$p_id,
                    "type" => \PDO::PARAM_INPUT_OUTPUT,
                    "length" => 255
                ],
                 "p_WORKSHOP_NO"        => $request->get("workshop_no"),
                 "p_WORKSHOP_NAME"      => $request->get("workshop_name"),
                 "p_WORKSHOP_NAME_BN"   => $request->get("workshop_name_bn"),
                 "p_ACTIVE_YN"          => $request->get("active_yn"),
                 "p_DEPARTMENT_ID"      =>$request->get("department_id"),
                 "p_EMPLOYEE_ID"        => $request->get("employee_id"),
                 "p_REMARKS"            => $request->get("remarks"),
                 "p_WORKSHOP_TYPE_ID"   => $request->get("workshop_type_id"),
                 "p_LOCATION_NAME"        => $request->get("location_name"),
                 "p_INSERT_BY"          =>  Auth()->ID(),
                 "o_status_code"        =>  &$statusCode,
                 "o_status_message"     =>  &$statusMessage
            ];

            DB::executeProcedure('MEA.VM_SETUP_ENTRY_PKG.workshop_entry', $params);

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
        $id = null;
        $querys = "select MEA.VM_SETUP_ENTRY_PKG.get_workshop_detail('".$id."') from dual" ;
        $entityList = DB::select($querys);
        return datatables()->of($entityList)
        ->addColumn('active_yn', function ($query) {
               if($query->active_yn == 'Y'){
                   return 'Active';
               }else{
                   return 'In-Active';
               }

            })
            ->addColumn('action', function ($query) {
                $baseUrl = request()->root();
                return '<a href="' . route('workshop-edit', $query->workshop_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })->make(true);
            //| <a target="_blank" href="'.$baseUrl.'/report/render/maintanance_details?xdo=/~weblogic/VMS/RPT_VEHICLE_MAINTENANCE.xdo&p_maintanance_id='.$query->workshop_id.'&type=pdf&filename=maintanance_details"  ><i class="bx bx-download cursor-pointer"></i></a>
    }

    public function edit($id)
    {
        if($id){
            $insertedData   = $this->commonVmsManager->findInsertedData("MEA.VM_SETUP_ENTRY_PKG.get_workshop_detail",$id);
        }

        $data =[
            'get_department'          => $this->commonVmsManager->commonDropDownLookupsList('vm_lookup_pkg.get_department',$insertedData->department_id),
            'get_workshop_type'       => $this->commonVmsManager->commonDropDownLookupsList('vm_lookup_pkg.get_workshop_type',$insertedData->workshop_type_id),
//            'get_location'            => $this->commonVmsManager->commonDropDownLookupsList('vm_lookup_pkg.get_location',$insertedData->location_id),
            'loadDecisionDropdown'    => $this->commonVmsManager->loadDecisionDropdown($insertedData->active_yn),
            'insertedData'            => $insertedData,
        ];

        return view('mea.vms.workshop.index',compact('data'));
    }

    public function update(Request $request, $id)
    {
        $params = $this->store($request, $id);
        if(isset($params['exception']) && ($params['exception'] == true)) {
            return $params;
        }
        $flashMessageContent = $this->flashMessageManager->getMessage($params);
        return redirect()->route('workshop-index')->with($flashMessageContent['class'], $flashMessageContent['message']);
    }

}
