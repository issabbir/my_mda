<?php

namespace App\Console\Commands;


use App\Entities\Mwe\MaintenanceReq;
use App\Entities\Mwe\MaintenanceSchedule;
use Carbon\Carbon;
use App\Helpers\HelperClass;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Array_;

class SchedulerVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Scheduler:verification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make request verification for auto scheduler';

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
        $schedule_reqs = MaintenanceReq::where('is_schedule_request', 'Y')
            ->where('status','1')
            ->get();
        if (count($schedule_reqs)>0) {
            $response='';
            foreach ($schedule_reqs as $req) {
                DB::beginTransaction();
                try {
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params = [
                        "P_MAINTENANCE_REQ_ID" => $req->id,
                        "O_STATUS_CODE" => &$status_code,
                        "O_STATUS_MESSAGE" => &$status_message,
                    ];
                    DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_SCHEDULE_REQUEST_VERIFY", $params);
                        $response = [
                            "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                            "status_code" => $params['O_STATUS_CODE'],
                            "data" => $params,
                            "status_message" => $params['O_STATUS_MESSAGE']
                        ];
                } catch (\Exception $e) {
                    DB::rollBack();
                    $response = ["status" => false, "status_code" => 99, "status_message" => 'Requisition verification make an exception.'];
                }
            }
            DB::commit();
            return  $this->info($response['status_message']);
        } else {
            return $this->info('No request found');
        }
    }
}
