<?php

namespace App\Console\Commands;

//use App\Contracts\Mwe\SettingsContract;
use App\Entities\Mwe\MaintenanceSchedule;
use Carbon\Carbon;
use App\Helpers\HelperClass;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Array_;

class AutoScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Scheduler:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make auto schedule for vessel maintenance';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $schedules = MaintenanceSchedule::where('status', 'P')
            ->whereNull('MAINTENANCE_REQ_ID')
            ->whereDate('NEXT_MAINTENANCE_AT', Carbon::today())
            ->get();
        if (count($schedules)>0) {
            $response='';
            foreach ($schedules as $schedule) {
                DB::beginTransaction();
                try {
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params = [
                        "P_ACTION_TYPE" => 'I',
                        "P_ID" => ["value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                        "P_REQUEST_NUMBER" => HelperClass::getRequestNumber($schedule->department_id),
                        "P_DEPARTMENT_ID" => $schedule->department_id,
                        "P_VESSEL_ID" => $schedule->vessel_id,
                        "P_VESSEL_MASTER_ID" => $schedule->vessel_master_id,
                        "p_DESCRIPTION" => '',
                        "P_REQUESTER_EMP_ID" => $schedule->doc_master_id,
                        "p_IS_SCHEDULE_REQUEST" => 'Y',
                        "P_CREATED_BY" => $schedule->doc_master_id,
                        "P_UPDATED_BY" => '',
                        "P_STATUS" => '',
                        "O_STATUS_CODE" => &$status_code,
                        "O_STATUS_MESSAGE" => &$status_message,
                    ];
                    DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_MAINT_REQS_INI_CUD", $params);

                    if ($params['O_STATUS_CODE'] === '1') {
                        try {
                            $schedule_status_code = sprintf("%4000s", "");
                            $schedule_status_message = sprintf("%4000s", "");
                            $sid = $schedule->id;
                            $schedule_params = [
                                "P_ACTION_TYPE" => 'S',
                                "P_ID" => ["value" => &$sid, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                                "P_MAINTENANCE_REQ_ID" => $params['P_ID']['value'],
                                "P_VESSEL_ID" => $schedule->vessel_id,
                                "P_VESSEL_MASTER_ID" => $params['P_VESSEL_MASTER_ID'],
                                "P_DOC_MASTER_ID" => $params['P_REQUESTER_EMP_ID'],
                                "P_DEPARTMENT_ID" => $schedule->department_id,
                                "P_LAST_MAINTENANCE_AT" => empty($schedule->last_maintenance_at) ? '' : $schedule->last_maintenance_at,
                                "P_NEXT_MAINTENANCE_AT" => empty($schedule->next_maintenance_at) ? '' : $schedule->next_maintenance_at,
                                "P_last_undocking_at" =>empty($schedule->last_undocking_at) ? '' : $schedule->last_undocking_at,
                                "P_CREATED_BY" => '',
                                "P_UPDATED_BY" => '',
                                "P_STATUS" => $schedule->status,
                                "O_STATUS_CODE" => &$schedule_status_code,
                                "O_STATUS_MESSAGE" => &$schedule_status_message,
                            ];
                            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_MAINTENANCE_SCHEDULE_CUD", $schedule_params);
                            $schedule_response = [
                                "status" => ($schedule_params['O_STATUS_CODE'] === '1') ? true : false,
                                "status_code" => $schedule_params['O_STATUS_CODE'],
                                "data" => $schedule_params,
                                "status_message" => $schedule_params['O_STATUS_MESSAGE']
                            ];
                        } catch (\Exception $e) {
                            $schedule_response = ["status" => false, "status_code" => 99, "status_message" => 'Schedule process make an exception.'];
                        }
                    }else{
                        $schedule_response = ["status" => false, "status_code" => 88, "status_message" => 'Schedule process make an exception.'];
                    }
                    if($schedule_response['status_code']==='1'){
                        $response = [
                            "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                            "status_code" => $params['O_STATUS_CODE'],
                            "data" => $params,
                            "status_message" => $params['O_STATUS_MESSAGE']
                        ];
                    }else{
                        $response = ["status" => false, "status_code" => 9999, "status_message" => 'Schedule process failed.'];
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    $response = ["status" => false, "status_code" => 999, "status_message" => 'Requisition process make an exception.'];
                }
            }
            DB::commit();
            return  $this->info($response['status_message']);
        } else {
            return $this->info('No schedule found');
        }
    }
}
