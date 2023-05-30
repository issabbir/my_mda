<?php

namespace App\Http\Controllers\Mea\Vms;

use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuelBulkEntryController extends Controller
{
    private $flashMessageManager;

    public function __construct(FlashMessageManager $flashMessageManager)
    {
        $this->flashMessageManager = $flashMessageManager;
    }

    public function index()
    {
        $data = [
            'get_fuel_consumption_types' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_fuel_consumption_types',1),
            'get_fuel_types' => $this->commonDropDownLookupsList('vm_lookup_pkg.get_fuel_types',4),
            'get_fuel_unit_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_fuel_unit',1),
            'get_vehicle_type' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_vehicle_type','','','ALL',0),

            'get_vehicle_reg_no_list' => $this->commonDropDownLookupsList('MEA.vm_VEHICLE_pkg.get'),
            'get_driver_reg_list' => $this->commonDropDownLookupsList('VM_DRIVER_PKG.get_driver_list'),
            'loadDecisionDropdown' => $this->loadDecisionDropdown(),
            'loadDepotTypeDropdown' => $this->loadDepotTypeDropdown('Y'),

            'get_work_type_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_work_type',1),
            'get_refuel_frequency_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_refuel_frequency',1),
        ];

        return view('mea.vms.fuelbulkentry.index', compact('data'));
    }


    public function commonDropDownLookupsList($look_up_name = null,$column_selected = null,$fetch_single_colid = null,$default_option = null,$default_option_value=null){
        $query = "Select ".$look_up_name."('".$fetch_single_colid."') from dual" ;
        $entityList = DB::select($query);
        $entityOption = [];
        $entityOption[] = "<option value='".$default_option_value."'>".($default_option? $default_option:"Please select an option")."</option>";
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
        $querys = "select MEA.VM_FUEL_LIMIT_ADDITION_PKG.get_fuel_limit_addition('" . $id . "') from dual";
        $entityList = DB::select($querys);
        return datatables()->of($entityList)
            ->addColumn('office_order_date', function ($query) {
                return Carbon::parse($query->office_order_date)->format('d-m-Y');
            })
            ->addColumn('active_from', function ($query) {
                return Carbon::parse($query->active_from)->format('d-m-Y');
            })

            ->addColumn('action', function ($query) {
                return '<a href="' . route('fuel-limit-addition-edit', $query->addition_fuel_limit_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })->make(true);

    }

    public function fuelBulkEntryList(){
        try {
            $query = "SELECT VI.VEHICLE_ID,
       VI.VEHICLE_REG_NO,
       VI.VEHICLE_TYPE_ID,
       NVL((SELECT VEHICLE_TYPE_NAME
  FROM MEA.L_VEHICLE_TYPE
  WHERE VEHICLE_TYPE_ID = VI.VEHICLE_TYPE_ID ),'N/A') VEHICLE_TYPE,
  DA.WORK_TYPE_ID,
  NVL((SELECT WORK_TYPE FROM MEA.L_WORK_TYPE where WORK_TYPE_ID = DA.WORK_TYPE_ID),'N/A') WORK_TYPE,
       VI.ENGINE_TYPE_ID,
       NVL((SELECT ENGINE_TYPE
  FROM MEA.L_ENGINE_TYPE
  where ENGINE_TYPE_ID = VI.ENGINE_TYPE_ID),'N/A') ENGINE_TYPE
  FROM MEA.VM_VEHICLE_INFO VI, VM_DRIVER_ASSIGNMENT DA
  where VI.VEHICLE_ID = DA.VEHICLE_ID(+)";

            return DB::select($query);
        } catch (\Exception $e) {
            return false;
        }

    }

    public function populateVehicleList(Request $request){
        $fuelConsumptionTypeId = $request->input('fuel_consumption_type_id');
        $fuelTypeId = $request->input('fuel_type_id');
        $qtyUnitId = $request->input('qty_unit_id');
        $refuelFrequencyId = $request->input('refuel_frequency_id');
        $vehicleTypeId = ($request->input('vehicle_type_id')==0)? '':$request->input('vehicle_type_id');

        try {
            if ($fuelConsumptionTypeId == '1') {
                $query = "select a.*,NVL(b.QTY,'0') QTY
            from(
                    SELECT VI.VEHICLE_ID,
                           VI.VEHICLE_REG_NO,
                           VI.VEHICLE_TYPE_ID,
                           VI.fuel_type_id,
                           NVL(
                           (SELECT VEHICLE_TYPE_NAME
                                  FROM MEA.L_VEHICLE_TYPE
                                  WHERE VEHICLE_TYPE_ID = VI.VEHICLE_TYPE_ID
                            ),'N/A') VEHICLE_TYPE,
                            DA.WORK_TYPE_ID,
                          NVL(
                          (SELECT WORK_TYPE
                                FROM MEA.L_WORK_TYPE
                                where WORK_TYPE_ID = DA.WORK_TYPE_ID
                            ),'N/A') WORK_TYPE,
                           VI.ENGINE_TYPE_ID,
                           NVL((SELECT ENGINE_TYPE
                                  FROM MEA.L_ENGINE_TYPE
                                  where ENGINE_TYPE_ID = VI.ENGINE_TYPE_ID
                            ),'N/A') ENGINE_TYPE
                      FROM MEA.VM_VEHICLE_INFO VI, VM_DRIVER_ASSIGNMENT DA
                      where VI.VEHICLE_ID = DA.VEHICLE_ID(+) ";
                if($vehicleTypeId){
                    $query .= " AND VI.VEHICLE_TYPE_ID = $vehicleTypeId ";
                }
      $query .= " ) a
              left join (
                        SELECT QTY,WORK_TYPE_ID
                        FROM MEA.FUEL_LIMIT
                        WHERE ACTIVE_TO IS NULL
                        AND (MAIN_FUEL_ID = $fuelTypeId OR $fuelTypeId IS NULL)
                        AND (QTY_UNIT_ID = $qtyUnitId OR $qtyUnitId IS NULL)
                        AND (REFUEL_FREQUENCY_ID = $refuelFrequencyId OR $refuelFrequencyId IS NULL)
              ) b on a.WORK_TYPE_ID = b.WORK_TYPE_ID ";

                /*
                 SELECT QTY
            FROM MEA.FUEL_LIMIT
            WHERE ACTIVE_TO IS NULL
                AND (MAIN_FUEL_ID = $fuelTypeId OR $fuelTypeId IS NULL)
            AND (QTY_UNIT_ID = $qtyUnitId OR $qtyUnitId IS NULL)
            AND (REFUEL_FREQUENCY_ID = $refuelFrequencyId OR $refuelFrequencyId IS NULL)
                 */
            } else if ($fuelConsumptionTypeId == '2') {
                $query = "select a.*,NVL(b.QTY,'0') QTY
                from(
                    SELECT VI.VEHICLE_ID,
                           VI.VEHICLE_REG_NO,
                           VI.VEHICLE_TYPE_ID,
                           VI.fuel_type_id,
                           NVL((SELECT VEHICLE_TYPE_NAME
                      FROM MEA.L_VEHICLE_TYPE
                      WHERE VEHICLE_TYPE_ID = VI.VEHICLE_TYPE_ID ),'N/A') VEHICLE_TYPE,
                      DA.WORK_TYPE_ID,
                      NVL((SELECT WORK_TYPE FROM MEA.L_WORK_TYPE where WORK_TYPE_ID = DA.WORK_TYPE_ID),'N/A') WORK_TYPE,
                           VI.ENGINE_TYPE_ID,
                           NVL((SELECT ENGINE_TYPE
                      FROM MEA.L_ENGINE_TYPE
                      where ENGINE_TYPE_ID = VI.ENGINE_TYPE_ID),'N/A') ENGINE_TYPE
                      FROM MEA.VM_VEHICLE_INFO VI, VM_DRIVER_ASSIGNMENT DA
                      where VI.VEHICLE_ID = DA.VEHICLE_ID(+) ";
                if($vehicleTypeId){
                    $query .= " AND VI.VEHICLE_TYPE_ID = $vehicleTypeId ";
                }
      $query .= "
              ) a
              left join (
                  SELECT fla.QTY,fla.WORK_TYPE_ID
                  FROM MEA.FUEL_LIMIT_ADDITION fla
                  WHERE fla.ACTIVE_TO IS NULL
                  AND (fla.QTY_UNIT_ID = $qtyUnitId OR $qtyUnitId IS NULL)
                  AND fla.REFUEL_FREQUENCY_ID = $refuelFrequencyId
              ) b on a.WORK_TYPE_ID = b.WORK_TYPE_ID ";
                /*$query = " SELECT QTY
            FROM MEA.FUEL_LIMIT_ADDITION
            WHERE ACTIVE_TO IS NULL
            AND (QTY_UNIT_ID = $qtyUnitId OR $qtyUnitId IS NULL)
            AND REFUEL_FREQUENCY_ID = $refuelFrequencyId ";*/
            }

            //return DB::select(DB::raw($query));
            return DB::select($query);

        } catch (\Exception $e) {
            return false;
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        if ($request->get('fuelBulkEntry')) {

                try {
                    $fuelBulkEntryTotal = $request->get('fuelBulkEntry');
                    $fuelBulkEntryCheckBox = $request->get('fuelBulkEntryCheckbox');

                    foreach ($fuelBulkEntryCheckBox as $fuelBulkEntryCheckBoxData) {
                     $fuelBulkEntry = $fuelBulkEntryTotal[$fuelBulkEntryCheckBoxData];

                    //foreach ($request->get('fuelBulkEntry') as $fuelBulkEntry) {
                            $p_id = '';
                            $o_status_code = sprintf("%4000s", "");
                            $o_status_message = sprintf('%4000s', '');
                            /*$fuelBulkEntryParams = [
                                'p_vehicle_id' => $fuelBulkEntry['vehicle_id'],
                                //'p_vehicle_reg_no' => $fuelBulkEntry['vehicle_reg_no'],
                                'p_vehicle_type_id' => $fuelBulkEntry['vehicle_type_id'],
                                'p_work_type_id' => $fuelBulkEntry['work_type_id']?$fuelBulkEntry['work_type_id']:'',
                                'p_mileage_on_refueling' => $fuelBulkEntry['mileage_on_refueling']?$fuelBulkEntry['mileage_on_refueling']:'',
                                'p_quantity' => $fuelBulkEntry['quantity']?$fuelBulkEntry['quantity']:0,
                                'p_engine_type_id' => $fuelBulkEntry['engine_type_id'],
                                "p_update_by" => auth()->user()->emp_id,
                                "o_status_code" => &$o_status_code,
                                "o_status_message" => &$o_status_message,
                            ];*/
                        $attachedFileContent = '';

                        $fuelBulkEntryParams = [
                            "p_FUEL_CONSUM_ID" => [
                                "value" => &$p_id,
                                "type" => \PDO::PARAM_INPUT_OUTPUT,
                                "length" => 255
                            ],
                            "p_VEHICLE_ID" => $fuelBulkEntry['vehicle_id'],
                            "p_REFUELING_DATE" => (($request->get("refueling_date")) ? date("Y-m-d", strtotime($request->get("refueling_date")))  : ''),
                            "p_MILEAGE_ON_REFUELING" => $fuelBulkEntry['mileage_on_refueling']?$fuelBulkEntry['mileage_on_refueling']:'',
                            "p_FUEL_QTY"        => $fuelBulkEntry['quantity']?$fuelBulkEntry['quantity']:0,
                            "p_FUEL_TYPE_ID"    => $fuelBulkEntry['fuel_type_id'],
                            "p_FUEL_CONSUMPTION_TYPE_ID" => '',//$request->get("fuel_consumption_type_id"),
                            "p_ADDITION_FUEL_YN"    => '',
                            //"p_CPA_DEPOT_YN"    => 'Y'
                            "p_FUEL_LIMIT_ID"           =>'',
                            "p_FUEL_LIMIT_ADITION_ID"   =>'',
                            "p_FUEL_UNIT_ID"            =>'',
                            "p_REFUEL_FREQUENCY_ID"     =>'',
                            "p_INSERT_BY"               =>  Auth()->ID(),
                            "o_status_code" => &$o_status_code,
                            "o_status_message" => &$o_status_message,
                        ];

                        DB::executeProcedure('mea.VM_FUEL_PKG.fuel_consum_bulk_entry', $fuelBulkEntryParams);
                        /*echo '<pre>';
                        print_r($fuelBulkEntryParams);*/

                        if ($fuelBulkEntryParams['o_status_code'] != 1) {
                            DB::rollBack();
                            return $fuelBulkEntryParams;
                        }
                    }
                    DB::commit();
                    $flashMessageContent = $this->flashMessageManager->getMessage($fuelBulkEntryParams);
                    return redirect()->back()->with($flashMessageContent['class'], $flashMessageContent['message']);

                    } catch (\Exception $e) {
                        DB::rollback();
                        return ["exception" => true, "o_status_code" => false, "o_status_message" => $e->getMessage()];
                    }
                /*
                } catch (\Exception $e) {
                    DB::rollBack();
                    $o_status_code = 99;
                    $o_status_message = $e->getMessage();
                }*/

        }

        /*DB::commit();

        if ($o_status_code == 1){
            return [
                'status' => true, "o_status_message" => $o_status_message
            ];
        } else {
            return [
                'status' => false, "o_status_message" => $o_status_message
            ];
        }*/

    }

}
