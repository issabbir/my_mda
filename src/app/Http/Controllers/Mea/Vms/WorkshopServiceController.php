<?php

namespace App\Http\Controllers\Mea\Vms;

use App\Contracts\Mea\Vms\CommonContract;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkshopServiceController extends Controller
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
            'get_workshop_type' => $this->commonVmsManager->commonDropDownLookupsList('vm_lookup_pkg.get_workshop_type'),
            'get_workshop' => $this->commonVmsManager->commonDropDownLookupsList('vm_lookup_pkg.get_workshop'),
            'get_workshop_service' => $this->commonVmsManager->commonDropDownLookupsList('vm_lookup_pkg.get_workshop_service'),

        ];
        return view('mea.vms.workshopservice.index', compact('data'));
    }

    public function store(Request $request,$id=null)
    {
        /* table name: WORKSHOP_SERVICE_MAP


work_ser_map_entry (p_WORK_SER_MAP_ID   IN OUT NUMBER,
                                 p_WORKSHOP_ID       IN     NUMBER,
                                 p_SERVICE_ID        IN     NUMBER,
                                 p_ACTIVE_YN         IN     VARCHAR2,
                                 p_INSERT_BY         IN     NUMBER,
                                 o_status_code          OUT NUMBER,
                                 o_status_message       OUT VARCHAR2)*/
        $params = [];
        DB::beginTransaction();
        try {
            $p_id = $id ? $id : '';
            $statusCode = sprintf("%4000s", "");
            $statusMessage = sprintf('%4000s', '');

            $params = [
                "p_WORK_SER_MAP_ID" => [
                    "value" => &$p_id,
                    "type" => \PDO::PARAM_INPUT_OUTPUT,
                    "length" => 255
                ],
                 "p_WORKSHOP_ID"        => $request->get("workshop_id"),
                 "p_SERVICE_ID"        => $request->get("service_id"),
                 "p_ACTIVE_YN"               => $request->get("active_yn"),
                 "p_INSERT_BY"          =>  Auth()->ID(),
                 "o_status_code"        =>  &$statusCode,
                 "o_status_message"     =>  &$statusMessage
            ];

            DB::executeProcedure('MEA.VM_SETUP_ENTRY_PKG.work_ser_map_entry', $params);

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
        $querys = $this->get_base_query();

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

                return '<a href="' . route('workshop-service-edit', $query->work_ser_map_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })->make(true);
    }


    private function get_base_query(){
        $querys = "SELECT WSP.WORK_SER_MAP_ID, WSP.ACTIVE_YN ,W.WORKSHOP_ID,W.WORKSHOP_NAME,WT.WORKSHOP_TYPE_ID,WT.WORKSHOP_TYPE_NAME,WS.SERVICE_ID,WS.SERVICE_NAME
FROM WORKSHOP_SERVICE_MAP WSP
INNER JOIN L_WORKSHOP W ON W.WORKSHOP_ID = WSP.WORKSHOP_ID
INNER JOIN L_WORKSHOP_SERVICES WS ON WS.SERVICE_ID = WSP.SERVICE_ID
INNER JOIN L_WORKSHOP_TYPES WT  ON WT.WORKSHOP_TYPE_ID = W.WORKSHOP_TYPE_ID " ;
        return $querys;
    }

    public function edit($id)
    {
        if($id){
            $insertedData   = DB::selectOne( $this->get_base_query() . " WHERE WSP.WORK_SER_MAP_ID  = $id");
        }

        $data = [
            'get_workshop_type' => $this->commonVmsManager->commonDropDownLookupsList('vm_lookup_pkg.get_workshop_type',$insertedData->workshop_type_id),
            'get_workshop' => $this->commonVmsManager->commonDropDownLookupsList('vm_lookup_pkg.get_workshop',$insertedData->workshop_id),
            'get_workshop_service' => $this->commonVmsManager->commonDropDownLookupsList('vm_lookup_pkg.get_workshop_service',$insertedData->service_id),
            'insertedData'            => $insertedData,

        ];


        return view('mea.vms.workshopservice.index',compact('data'));
    }

    public function update(Request $request, $id)
    {
        $params = $this->store($request, $id);
        if(isset($params['exception']) && ($params['exception'] == true)) {
            return $params;
        }
        $flashMessageContent = $this->flashMessageManager->getMessage($params);
        return redirect()->route('workshop-service-index')->with($flashMessageContent['class'], $flashMessageContent['message']);
    }

}
