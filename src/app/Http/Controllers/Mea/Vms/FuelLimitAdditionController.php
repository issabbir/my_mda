<?php

namespace App\Http\Controllers\Mea\Vms;

use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuelLimitAdditionController extends Controller
{
    private $flashMessageManager;

    public function __construct(FlashMessageManager $flashMessageManager)
    {
        $this->flashMessageManager = $flashMessageManager;
    }

    public function index()
    {
        $data = [
            'get_work_type_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_work_type'),
            'get_engine_type_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_engine_type'),
            'get_fuel_unit_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_fuel_unit'),
            'get_refuel_frequency_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_refuel_frequency'),
        ];

        return view('mea.vms.fuellimitaddition.index', compact('data'));
    }

    public function commonDropDownLookupsList($look_up_name = null, $column_selected = null, $fetch_single_colid = null)
    {
        $query = "Select " . $look_up_name . "('" . $fetch_single_colid . "') from dual";
        $entityList = DB::select($query);
        $entityOption = [];
        $entityOption[] = "<option value=''>Please select an option</option>";
        foreach ($entityList as $item) {
            $entityOption[] = "<option value='" . $item->pass_value . "'" . ($column_selected == $item->pass_value ? 'selected' : '') . ">" . $item->show_value . "</option>";
        }
        return $entityOption;
    }

    private function findInsertedData($pkgFunction, $primaryId = null, $multipleRow = null)
    {
        $querys = "SELECT " . $pkgFunction . "('" . $primaryId . "') from dual";
        $entityList = DB::select($querys);

        if (isset($primaryId) && !isset($multipleRow)) {
            $array1d = $entityList[0];
            return $array1d;
        }
        return $entityList;
    }

    public function store(Request $request, $id = null)
    {

        $params = [];
        DB::beginTransaction();
        try {
            $p_id = $id ? $id : '';
            $statusCode = sprintf("%4000s", "");
            $statusMessage = sprintf('%4000s', '');

            $params = [
                "p_ADDITION_FUEL_LIMIT_ID" => [
                    "value" => &$p_id,
                    "type" => \PDO::PARAM_INPUT_OUTPUT,
                    "length" => 255
                ],

                "p_OFFICE_ORDER_NO" => $request->get("office_order_no"),
                "p_OFFICE_ORDER_DATE" => (($request->get("office_order_date")) ? date("Y-m-d", strtotime($request->get("office_order_date"))) : ''),
                "p_MINISTRY_ORDER" => $request->get("ministry_order"),
                "p_MINISTRY_ORDER_DATE" => (($request->get("ministry_order_date")) ? date("Y-m-d", strtotime($request->get("ministry_order_date"))) : ''),
                "p_BOARD_MEETING_NO" => $request->get("board_meeting_no"),
                "p_BOARD_MEETING_DATE" => (($request->get("board_meeting_date")) ? date("Y-m-d", strtotime($request->get("board_meeting_date"))) : ''),
                "p_WORK_TYPE_ID" => $request->get("work_type_id"),
                "p_ENGINE_TYPE_ID" => $request->get("engine_type_id"),
                "p_QTY" => $request->get("qty"),
                "p_QTY_UNIT_ID" => $request->get("qty_unit_id"),
                "p_REFUEL_FREQUENCY_ID" => $request->get("refuel_frequency_id"),
                "p_ACTIVE_FROM" => (($request->get("active_from")) ? date("Y-m-d", strtotime($request->get("active_from"))) : ''),
                "p_ACTIVE_TO" => (($request->get("active_to")) ? date("Y-m-d", strtotime($request->get("active_to"))) : ''),
                "p_INSERT_BY" => Auth()->ID(),
                "o_status_code" => &$statusCode,
                "o_status_message" => &$statusMessage
            ];

            DB::executeProcedure('MEA.VM_FUEL_LIMIT_ADDITION_PKG.fuel_limit_addition_entry', $params);

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


    public function edit($id)
    {
        if ($id) {
            $insertedData = $this->findInsertedData("MEA.VM_FUEL_LIMIT_ADDITION_PKG.get_fuel_limit_addition", $id);
        }

        $data = [
            'get_work_type_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_work_type', $insertedData->work_type_id),
            'get_engine_type_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_engine_type', $insertedData->engine_type_id),
            'get_fuel_unit_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_fuel_unit', $insertedData->qty_unit_id),
            'get_refuel_frequency_list' => $this->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_refuel_frequency', $insertedData->refuel_frequency_id),
            'insertedData' => $insertedData,
        ];

        return view('mea.vms.fuellimitaddition.index', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $params = $this->store($request, $id);
        if(isset($params['exception']) && ($params['exception'] == true)) {
            return $params;
        }

        $flashMessageContent = $this->flashMessageManager->getMessage($params);
        return redirect()->route('fuel-limit-addition-index')->with($flashMessageContent['class'], $flashMessageContent['message']);
    }
}
