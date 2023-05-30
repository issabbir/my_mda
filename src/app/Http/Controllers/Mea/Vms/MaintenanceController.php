<?php

namespace App\Http\Controllers\Mea\Vms;

use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
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
           // 'get_workshop_list' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_workshop'),
            'get_workshop_type' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_workshop_type'),
            'get_workshop_service' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_workshop_service'),
            'get_location' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_location',1),

            'loadDecisionDropdown' => $this->loadDecisionDropdown(),

        ];
        return view('mea.vms.maintenance.index', compact('data'));
    }

    public function datatableList()
    {
        $id = null;
        $querys = "select MEA.VM_MAINTANANCE_PKG.get_maintain_master('".$id."') from dual" ;
        $entityList = DB::select($querys);
        return datatables()->of($entityList)
            ->addColumn('job_date', function($query) {
                return Carbon::parse($query->job_date)->format('d-m-Y');
            })
            ->addColumn('job_end_date', function($query) {
                return Carbon::parse($query->job_end_date)->format('d-m-Y');
            })
            ->addColumn('action', function ($query) {
                $baseUrl = request()->root();
                return '<a href="' . route('maintenance-edit', $query->maintanance_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>
                        | <a target="_blank" href="'.$baseUrl.'/report/render/maintanance_details?xdo=/~weblogic/VMS/RPT_VEHICLE_MAINTENANCE.xdo&p_maintanance_id='.$query->maintanance_id.'&type=pdf&filename=maintanance_details"  ><i class="bx bx-download cursor-pointer"></i></a>';
            })->make(true);
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
            $insertedData   = $this->findInsertedData("MEA.VM_MAINTANANCE_PKG.get_maintain_master",$id);
            $insertedDetailsData   = $this->findInsertedData("MEA.VM_MAINTANANCE_PKG.get_maintain_detail",$id,'Y');

            $insertedJobByData   = $this->findInsertedData("MEA.VM_MAINTANANCE_PKG.get_job_by_detail",$insertedData->job_by);
            $insertedJobReqByData   = $this->findInsertedData("MEA.VM_MAINTANANCE_PKG.get_job_req_by",$insertedData->job_request_by);

        }

        $data =[
            'get_vehicle_reg_no_list' => $this->commonDropDownLookupsList('MEA.vm_VEHICLE_pkg.get',$insertedData->vehicle_id),
            'get_driver_reg_list'     => $this->commonDropDownLookupsList('VM_DRIVER_PKG.get_driver_list',$insertedData->driver_id),
            'get_workshop_list'       => $this->commonDropDownLookupsListFowWorkShopService($insertedData->workshop_id),
            'get_workshop_type'       => $this->commonDropDownLookupsList('vm_lookup_pkg.get_workshop_type',$insertedData->workshop_type_id),
            'get_workshop_service'    => $this->commonDropDownLookupsList('vm_lookup_pkg.get_workshop_service',$insertedData->workshop_type_id),
            'get_location'            => $this->commonDropDownLookupsList('vm_lookup_pkg.get_location',$insertedData->location_id),

            'insertedData'            => $insertedData,
            'insertedDetailsData'     => $insertedDetailsData,
            'insertedJobByData'       => $insertedJobByData,
            'insertedJobReqByData'    => $insertedJobReqByData,
        ];

        return view('mea.vms.maintenance.index',compact('data'));
    }

    private function commonDropDownLookupsListFowWorkShopService($workshop_id){
        $query = "SELECT W.WORKSHOP_ID,W.WORKSHOP_NAME FROM L_WORKSHOP W WHERE W.WORKSHOP_ID = $workshop_id ";
        $entityList = DB::select($query);
        $entityOption = [];
        //$entityOption[] = "<option value=''>Please select an option</option>";
        foreach ($entityList as $item) {
            $entityOption[] = "<option value='".$item->workshop_id."'".($workshop_id == $item->workshop_id ? ' selected':'').">".$item->workshop_name."</option>";
        }
        return $entityOption;
    }

    public function update(Request $request, $id)
    {
        $params = $this->store($request, $id);
        if(isset($params['exception']) && ($params['exception'] == true)) {
            return $params;
        }
        $flashMessageContent = $this->flashMessageManager->getMessage($params);
        return redirect()->route('maintenance-index')->with($flashMessageContent['class'], $flashMessageContent['message']);
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
                "p_MAINTANANCE_ID" => [
                    "value" => &$p_id,
                    "type" => \PDO::PARAM_INPUT_OUTPUT,
                    "length" => 255
                ],
                "p_JOB_NO"          => $request->get("job_no"),
                "p_JOB_DATE"        => (($request->get("job_date")) ? date("Y-m-d", strtotime($request->get("job_date"))) : ''),
                "p_JOB_END_DATE"    => (($request->get("job_end_date")) ? date("Y-m-d", strtotime($request->get("job_end_date"))) : ''),
                "p_VEHICLE_ID"      => $request->get("vehicle_id"),
                "p_JOB_BY"          => $request->get("job_by"),
                "p_COMMENTS"        => $request->get("remarks"),
                //"p_JOB_COST"        => $request->get("job_cost"),
                "p_JOB_REQUEST_BY"  => $request->get("job_request_id"),
                "p_WORKSHOP_ID"     => $request->get("workshop_id"),
                "p_WORKSHOP_TYPE_ID" => $request->get("workshop_type_id"),
                "p_LOCATION_ID"     => $request->get("location_id"),
                //"p_VEHICLE_REG_NO"  => $request->get(""),
                "p_DRIVER_ID"       => $request->get("driver_id"),
                "p_INSERT_BY"       =>  Auth()->ID(),
                "o_status_code"     =>  &$statusCode,
                "o_status_message"  =>  &$statusMessage
            ];

            DB::executeProcedure('MEA.VM_MAINTANANCE_PKG.maintain_mst_entry', $params);

            $maintanance_details_id  = ($request->get('maintanance_details_id'))? $request->get('maintanance_details_id'): array();
            $service_id        = ($request->get('service_id'))? $request->get('service_id'): array();
            $service_date      = ($request->get('service_date'))? $request->get('service_date'): array();
            $service_cost      = ($request->get('service_cost'))? $request->get('service_cost'): array();
            $comments          = ($request->get('comments'))? $request->get('comments'): array();

            if ((count($maintanance_details_id)>0) && ($params["o_status_code"] == true)) {
                $pk_id = $params['p_MAINTANANCE_ID']['value'];

                foreach ($service_id as $key => $value) {
                    $fk_id = $maintanance_details_id[$key]? $maintanance_details_id[$key]:'';
                    $params2 = [];
                    $statusCode = sprintf("%4000s", "");
                    $statusMessage = sprintf('%4000s', '');
                    $params2 = [
                        "p_MAINTANANCE_DETAILS_ID" => [
                            "value" => &$fk_id,
                            "type" => \PDO::PARAM_INPUT_OUTPUT,
                            "length" => 255
                        ],
                        "p_SERVICE_DATE"   => (($service_date[$key]) ? date("Y-m-d", strtotime($service_date[$key])) : ''),
                        "p_COMMENTS"       => $comments[$key],
                       // "p_SERVICE_COST"   => $service_cost[$key]?$service_cost[$key]:0,
                        "p_MAINTANANCE_ID" => $pk_id,
                        "p_SERVICE_ID"     => $service_id[$key],
                        "p_WORKSHOP_ID"    => $request->get("workshop_id"),
                        "p_INSERT_BY"      => Auth()->ID(),
                        "o_status_code"    => &$statusCode,
                        "o_status_message" => &$statusMessage
                    ];
                    DB::executeProcedure('MEA.VM_MAINTANANCE_PKG.maintain_detail_entry', $params2);

                }
            }else{
                DB::rollback();
                $flashMessageContent = $this->flashMessageManager->getMessage(["exception" => true, "o_status_code" => false, "o_status_message" => 'Please add services']);
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

    public function deleteDetails(Request $request, $id){

        if($id){
            DB::beginTransaction();

            try {
                    $params = [];
                    $statusCode = sprintf("%4000s", "");
                    $statusMessage = sprintf('%4000s', '');
                    $params = [
                        "p_MAINTANANCE_DETAILS_ID" => [
                            "value" => &$id,
                            "type" => \PDO::PARAM_INPUT_OUTPUT,
                            "length" => 255
                        ],
                        "o_status_code"    => &$statusCode,
                        "o_status_message" => &$statusMessage
                    ];

                    DB::executeProcedure('MEA.VM_MAINTANANCE_PKG.maintain_detail_del', $params);
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
