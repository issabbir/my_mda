<?php

namespace App\Http\Controllers\Mea\Vms;

use App\Contracts\Mea\Vms\CommonContract;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class trackerController extends Controller
{

	private $flashMessageManager;
    private $commonVmsManager;

    public function __construct(CommonContract $commonVmsManager, FlashMessageManager $flashMessageManager)
    {
        $this->commonVmsManager    = $commonVmsManager;
        $this->flashMessageManager = $flashMessageManager;
    }
    //
    public function index(){

    	 $data = [
           'loadDecisionDropdown' => $this->commonVmsManager->loadDecisionDropdown('Y'),
        ];
        return view('mea.vms.tracker.index', compact('data'));//

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
                "p_TRACKER_ID" => [
                    "value" => &$p_id,
                    "type" => \PDO::PARAM_INPUT_OUTPUT,
                    "length" => 255
                ],
                "p_TRACKER_COMPANY_NAME" => $request->get("tracker_company_name"),
                "p_TRACKER_DEVICE_IMEI" => $request->get("tracker_device_imei"),
                "p_ACTIVE_YN" => $request->get("active_yn"),
                "p_INSERT_BY"          =>  Auth()->ID(),
                "o_status_code"        =>  &$statusCode,
                "o_status_message"     =>  &$statusMessage
            ];

            DB::executeProcedure('MEA.VM_SETUP_ENTRY_PKG.tracker_device_entry', $params);

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
            $insertedData   = $this->commonVmsManager->findInsertedData("MEA.VM_SETUP_ENTRY_PKG.get_tracker_record",$id);
        }

        $data =[
            'loadDecisionDropdown'    => $this->commonVmsManager->loadDecisionDropdown($insertedData->active_yn),
            'insertedData'            => $insertedData,
        ];

        return view('mea.vms.tracker.index',compact('data'));
    }

    public function update(Request $request, $id)
    {
        $params = $this->store($request, $id);
        if(isset($params['exception']) && ($params['exception'] == true)) {
            return $params;
        }
        $flashMessageContent = $this->flashMessageManager->getMessage($params);
        return redirect()->route('tracker-index')->with($flashMessageContent['class'], $flashMessageContent['message']);
    }

    public function datatableList()
    {
        $id = null; 

        $querys = "select MEA.VM_SETUP_ENTRY_PKG.get_tracker_record('".$id."') from dual" ;
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
                return '<a href="' . route('tracker-edit', $query->tracker_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })->make(true);
        //| <a target="_blank" href="'.$baseUrl.'/report/render/maintanance_details?xdo=/~weblogic/VMS/RPT_VEHICLE_MAINTENANCE.xdo&p_maintanance_id='.$query->workshop_id.'&type=pdf&filename=maintanance_details"  ><i class="bx bx-download cursor-pointer"></i></a>
    }
}
