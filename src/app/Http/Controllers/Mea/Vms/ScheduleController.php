<?php

namespace App\Http\Controllers\Mea\Vms;

use App\Contracts\Mea\Vms\CommonContract;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    private $flashMessageManager;
    private $commonVmsManager;

    public function __construct(CommonContract $commonVmsManager, FlashMessageManager $flashMessageManager)
    {
        $this->commonVmsManager = $commonVmsManager;
        $this->flashMessageManager = $flashMessageManager;
    }

    public function index()
    {
        $data = [
            'loadDecisionDropdown' => $this->commonVmsManager->loadDecisionDropdown('Y'),
        ];
        return view('mea.vms.schedule.index', compact('data'));
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
                "p_SCHEDULE_ID" => [
                    "value" => &$p_id,
                    "type" => \PDO::PARAM_INPUT_OUTPUT,
                    "length" => 255
                ],
                "p_SCHEDULE_NO" => $request->get("schedule_no"),
                "p_SCHEDULE" => $request->get("schedule"),
                "p_SCHEDULE_BN" => $request->get("schedule_bn"),
                "p_DESCRIPTION" => $request->get("description"),
                "p_DESCRIPTION_BN" => $request->get("description_bn"),
                "p_ACTIVE_YN" => $request->get("active_yn"),
                "p_INSERT_BY" => Auth()->ID(),
                "o_status_code" => &$statusCode,
                "o_status_message" => &$statusMessage
            ];

            DB::executeProcedure('MEA.VM_SETUP_ENTRY_PKG.schedule_entry', $params);

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
        if ($id) {
            $insertedData = $this->commonVmsManager->findInsertedData("MEA.VM_SETUP_ENTRY_PKG.get_schedule_list", $id);
        }

        $data = [
            'insertedData' => $insertedData,
        ];

        return view('mea.vms.schedule.index', compact('data'));
    }


    public function update(Request $request, $id)
    {
        $params = $this->store($request, $id);
        if (isset($params['exception']) && ($params['exception'] == true)) {
            return $params;
        }
        $flashMessageContent = $this->flashMessageManager->getMessage($params);
        return redirect()->route('schedule-index')->with($flashMessageContent['class'], $flashMessageContent['message']);
    }


    public function datatableList()
    {
        $id = null;

        $querys = "select MEA.VM_SETUP_ENTRY_PKG.get_schedule_list('" . $id . "') from dual";
        $entityList = DB::select($querys);
        return datatables()->of($entityList)
            ->addColumn('active_yn', function ($query) {
                if ($query->active_yn == 'Y') {
                    return 'Active';
                } else {
                    return 'In-Active';
                }

            })
            ->addColumn('action', function ($query) {
                return '<a href="' . route('schedule-edit', $query->schedule_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })->make(true);
    }


}
