<?php
namespace App\Managers\Cms;

use App\Contracts\Cms\CommonContract;
use App\Entities\Cms\CpaVessel;
use App\Entities\Cms\FuelConsumptionMst;
use App\Entities\Cms\LPlacement;
use App\Entities\Cms\LPlacementType;
use App\Entities\Cms\Shifting;
use App\Entities\Cms\WorkFlowDetail;
use App\Entities\Cms\WorkFlowMapping;
use App\Entities\Cms\WorkFlowMaster;
use App\Entities\Pmis\Employee\Employee;
use App\Enums\Cms\ConfigRole;
use App\Enums\Pmis\Employee\Statuses;
use App\Helpers\HelperClass;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class  CommonManager implements CommonContract
{
    protected $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }
    public function getCalenderMonths()
    {
        $months = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'Jun', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
        return $months;
    }

    public function getCalenderYear()
    {
        $i = date("Y")+1; //1971
        $y=date("Y")+2;
        $year=array();
        for (; ; ) {
            $j=$y-1;
            if ($y < $i) {
                break;
            }
            $year[]=["text"=>$j,"value"=>$j];
            $y--;
        }
        return $year;
    }

    public function getAllWorkFlowMaster()
    {
        return WorkFlowMaster::all()->sortBy('workflow_name');
    }

    public function showWorkflowData($request = [])
    {
        $userRole=Auth::user()->getRoles()->pluck('role_key')->toArray();
        $workflow_dtl_id=WorkFlowDetail::whereIn('role',$userRole)->pluck('workflow_detail_id')->toArray();
        return WorkFlowMapping::with('workflow_master','workflow_detail','consumption')
            ->where('workflow_master_id',$request->get('workflow_master_id'))
            ->whereIn('workflow_detail_id',$workflow_dtl_id)
            ->whereNull('user_id')
            ->get();
    }

    public function showAuthorizedData($request = [])
    {
        if (strtolower($request->get('ref_ob')) == strtolower('CM_FUEL_CONSUMPTION_MST')) {
            return FuelConsumptionMst::with('fuel_items')
                ->where('fuel_consumption_mst_id', $request->get('ref_id'))
                ->first();
        } else {
            return null;
        }
    }

    public function showMappingData($id)
    {
        $workFlowMasterId=WorkFlowMapping::where('reference_id',$id)->value('workflow_master_id');
        $allActiveWorkFlow=WorkFlowDetail::where('workflow_master_id',$workFlowMasterId)
            ->where('status','A')
            ->with('workflow_history')
            ->orderBy('seq','asc')
            ->get();
        $curWorkFlowStep=WorkFlowMapping::where('reference_id',$id)
            ->with('workflow_detail')
            ->orderBy('insert_date','desc')
            ->first();

        $workflowHistory=WorkFlowMapping::where('reference_id',$id)
            ->whereNotNull('user_id')
            ->with('auth_related_emp','workflow_master','workflow_detail')
            ->orderBy('workflow_mapping_id','desc')
            ->get();
//        dd($workflowHistory);
        $approval_count=WorkFlowMapping::where('reference_id',$id)
            ->where('reference_status','Y')
            ->get();
        $reject_count=WorkFlowMapping::where('reference_id',$id)
            ->where('reference_status','R')
            ->get();
        $proceed_step=count($approval_count)-count($reject_count);
        $all_stage=WorkFlowMapping::where('reference_id',$id)->pluck('workflow_mapping_id');
//        $media_doc=MediaFile::whereIn('ref_id',$all_stage)->latest('ref_id')->first();
//        $agent_step_seq=WorkFlowDetail::where('role','vsl_bill_agent_approval')->where('workflow_master_id',$workFlowMasterId)->value('seq');
        return [
            'allActiveWorkFlow'=>$allActiveWorkFlow,
            'curWorkFlowStep'=>$curWorkFlowStep,
            'workflowHistory'=>$workflowHistory,
            'proceed_step'=>$proceed_step,
//            'media_doc'=>$media_doc,
//            'agent_step_seq'=>$agent_step_seq
        ];
    }

    public function getPlacementType()
    {
        return LPlacementType::where('status','A')
            ->orderBy('type_name','asc')
            ->get();
    }

    public function getPlacement()
    {
        return LPlacement::where('status','A')
                ->orderBy('placement_name','asc')
                ->get();

    }
    public function getPlacementVessel()
    {
//        return DB::table('MDA.CPA_VESSELS')
//            ->orderBy('name')
//                ->get();
//        vessel_type','fuel_type','employee
            return CpaVessel::where('status','A')
                ->orderBy('name','asc')
                ->get();

    }
    public function getVessel(){
        return DB::table('MDA.CPA_VESSELS')
            ->orderBy('name')
                ->get();
    }
    public function getOffDayList($year,$month)
    {
        $date=new \DateTime($year.'-'.$month.'-'.'01');
        $make_date=$date->format('Y-m-d');
        $last_day=substr(date("Y-m-t", strtotime($make_date)),'8','9');

        $i =1;
        $y=$last_day;
        $day=array();

        for ($i;$i<=$y;$i++){
            $day[]=["text"=>str_pad($i, 2, '0', STR_PAD_LEFT),"value"=>str_pad($i, 2, '0', STR_PAD_LEFT)];
        }
        return $day;

    }

    public function getLastConsumption($vessel_id)
    {
        $sql = <<<QUERY
SELECT *
  FROM (  SELECT TO_CHAR (CM.RECEIVED_DATE, 'DD/MM/YYYY')
                     AS last_received_date,
                 Cm.PRV_RESERVED_FUEL,
                 CM.RECEIVED_FUEL,
                 CM.FUEL_CONSUMPTION_MST_ID,
                 CM.CONSUMPTION_REF_NO
                     last_fuel_consumption_ref_no
            FROM MDA.CPA_VESSELS CV, mda.CM_FUEL_CONSUMPTION_MST CM
           WHERE     CM.CPA_VESSEL_ID = CV.ID
                 AND CM.STATUS = 'A'
                 AND CM.CPA_VESSEL_ID = :P_VESSEL_ID
        ORDER BY CM.CREATED_DATE DESC)
 WHERE ROWNUM = 1

QUERY;

//        dd($sql);
       return $data = DB::selectOne($sql,
            ['p_vessel_id' => $vessel_id]
        );

    }

    public function getDutyShiftByEmployeeDuty($year,$month){
        $date=new \DateTime($year.'-'.$month.'-'.'01');
        $make_date=$date->format('Y-m-d');
        $last_day=date("Y-m-t", strtotime($make_date));
        return Shifting::where('effective_to_date','>=',$last_day)
               ->orderBy('shift_name','asc')
               ->get();
//        Shifting::all()->sortBy('shift_name');
    }

    public function getFuelConsumptionNoByVessel($id){
        return FuelConsumptionMst::where('cpa_vessel_id','=',$id)
            ->orderBy('created_date','desc')
            ->get();
    }
    public function searchShiftingEmployee($name)
    {
        return $this->employee->where(
            [
                ['emp_status_id', '=', Statuses::ON_ROLE]
            ]
        )//->whereIn('dpt_department_id',ConfigRole::shifting_dept)
            //->where('emp_grade_id','>=',ConfigRole::max_shifting_grade)
            ->where(function($query) use ($name){
            $query->where(DB::raw('LOWER(employee.emp_name)'),'like',strtolower('%'.trim($name).'%'))
                ->orWhere('employee.emp_code', 'like', ''.trim($name).'%' );
        })->orderBy('emp_code', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name','emp_grade_id', DB::raw("to_char(emp_join_date,'dd/mm/yyyy') as emp_join_date")]);
    }

    public function getEmployee($id)
    {
        return $this->employee->where(function($query) use ($id){
                $query->where('emp_id','=',$id);
            })
            ->first(['emp_id', 'emp_code', 'emp_name','emp_grade_id', DB::raw("to_char(emp_join_date,'dd/mm/yyyy') as emp_join_date")]);
    }
}
