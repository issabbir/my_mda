<?php

namespace App\Http\Controllers\Mea\Vms;

use App\Contracts\Mea\Vms\CommonContract;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleRentController extends Controller
{   //$this->commonDropDownLookupsList('MEA.vm_VEHICLE_pkg.get'),
    private $flashMessageManager;
    private $commonVmsManager;

    public function __construct(CommonContract $commonVmsManager, FlashMessageManager $flashMessageManager)
    {
        $this->commonVmsManager    = $commonVmsManager;
        $this->flashMessageManager = $flashMessageManager;
    }
    public function index(){
       $data = [
           'get_vehicle_reg_no_list' => $this->commonVmsManager->commonDropDownLookupsList('Mea.vm_VEHICLE_pkg.get_outside_vehicle_list'),
        ];
        return view('mea.vms.vehiclesrent.index',compact('data'));
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
                "p_VEHICLE_RENT_ID" => [
                    "value" => &$p_id,
                    "type" => \PDO::PARAM_INPUT_OUTPUT,
                    "length" => 255
                ],
                "p_VEHICLE_ID"      => $request->get("vehicle_id"),
                "p_RENT_START_DATE" => (($request->get("rent_start_date")) ? date("Y-m-d", strtotime($request->get("rent_start_date")))  : ''),
                "p_RENT_END_DATE"   => (($request->get("rent_end_date")) ? date("Y-m-d", strtotime($request->get("rent_end_date")))  : ''),
                "p_INSERT_BY"       =>  Auth()->ID(),
                "o_status_code"     =>  &$statusCode,
                "o_status_message"  =>  &$statusMessage
            ];

            DB::executeProcedure('MEA.VM_VEHICLE_PKG.vehicle_rent_entry', $params);

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

    public function datatableList(Request $request)
    {
        $id =  $request->get("vehicle_id");
        $querys = "select MEA.VM_VEHICLE_PKG.get_vehicle_rent('".$id."') from dual" ;
        $entityList = DB::select($querys);
        return datatables()->of($entityList)
               ->addColumn('rent_start_date', function($query) {
                   return Carbon::parse($query->rent_start_date)->format('d-m-Y');
               })
               ->addColumn('rent_end_date', function($query) {
                   return Carbon::parse($query->rent_end_date)->format('d-m-Y');
               })

            ->addColumn('action', function ($query) {
                return '<a href="' . route('vehicle-rent-edit', $query->vehicle_rent_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })
            ->make(true);
    }

    public function edit($id)
    {
        if($id){
            $insertedData          = $this->commonVmsManager->findInsertedData("MEA.vm_VEHICLE_pkg.get_vehicle_rent_row",$id);
        }
        $data =[
            'get_vehicle_reg_no_list' => $this->commonVmsManager->commonDropDownLookupsList('Mea.vm_VEHICLE_pkg.get_outside_vehicle_list',$insertedData->vehicle_id),
            'insertedData'            => $insertedData,
        ];
        return view('mea.vms.vehiclesrent.index',compact('data'));
    }

    public function update(Request $request, $id)
    {
        $params = $this->store($request, $id);
        if(isset($params['exception']) && ($params['exception'] == true)) {
            return $params;
        }
        $flashMessageContent = $this->flashMessageManager->getMessage($params);
        return redirect()->route('vehicle-rent-index')->with($flashMessageContent['class'], $flashMessageContent['message']);
    }

}
