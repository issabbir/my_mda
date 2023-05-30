<?php
namespace App\Managers\Cms;

use App\Contracts\Cms\ShiftingContract;
use App\Entities\Cms\EmployeeDuties;
use App\Entities\Cms\EmployeeDutyShifting;
use App\Entities\Cms\EmployeeOffDay;
use App\Entities\Pmis\Employee\Employee;
use App\Helpers\HelperClass;
use Illuminate\Support\Facades\DB;

class ShiftingManager implements ShiftingContract
{
    public function dutiesStore($action_type=null, $request = [], $id=null)
    {
        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_EMPLOYEE_DUTY_ID"  => ["value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255],
                "P_EMPLOYEE_ID" => $request->get("employee_id"),
                "P_STATUS" => $request->get("status"),
                "P_DESIGNATION_ID" => $request->get('designation_id'),
                "P_PLACEMENT_ID" => ($request->get('placement_id'))?$request->get('placement_id'):$request->get('placement_vessel_id'),
                "P_JOINING_DATE" =>(!$request->get("joining_date"))?'':date("Y-m-d", strtotime($request->get("joining_date"))),
                "P_CREATED_BY" => auth()->id(),
                "P_DUTY_MONTH" => $request->get('duty_month'),
                "P_DUTY_YEAR" => $request->get('duty_year'),
                "P_DOC_REFERENCE_NO" => $request->get('doc_reference_no'),
                "P_PLACEMENT_TYPE_ID" => $request->get('placement_type_id'),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.CM_ENGINE.CM_EMPLOYEE_DUTIES_CUD", $params);

            if ($params['O_STATUS_CODE'] ==='1'){
                $this->offDayStore($action_type,$request,($id)?$id:$params['P_EMPLOYEE_DUTY_ID']['value']);
            }
            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        DB::commit();
        return $response;
    }

    public function dutiesData()
    {
        return EmployeeDuties::orderBy("created_date", 'desc')->get();
    }

    public function dutyShiftingStore($action_type=null, $request = [], $id=null)
    {
        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_EMP_DUTY_SHIFTING_ID"  => $id,
                "P_EMPLOYEE_DUTY_ID"  => $request->get("employee_duty_id"),
                "P_EFFECTIVE_FROM_DATE" =>(!$request->get("effective_from_date"))?'':date("Y-m-d", strtotime($request->get("effective_from_date"))),
                "P_EFFECTIVE_TO_DATE" =>(!$request->get("effective_to_date"))?'':date("Y-m-d", strtotime($request->get("effective_to_date"))),
                "P_STATUS" => $request->get('status'),
                "P_CREATED_BY" => auth()->id(),
                "P_SHIFT_ID" => $request->get("shift_id"),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];
            DB::executeProcedure("MDA.CM_ENGINE.CM_EMP_DUTIESHIFTING_CUD", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function dutyShiftingData($request=[])
    {
        return EmployeeDutyShifting::where('employee_duty_id',$request->get('employee_duty_id'))
            ->orderBy("created_date", 'desc')->get();
    }

    public function offDayStore($action_type=null, $request = [], $id=null)
    {

        DB::beginTransaction();

        if($action_type=='U'){
            $deleted_data=EmployeeOffDay::where('employee_duty_id', '=', $id)->delete();
        }

        try {
            foreach ($request->get('off_day') as $key=>$value) {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $date=new \DateTime($request->get('duty_year').'-'.$request->get('duty_month').'-'.$request->get('off_day')[$key]);
                $off_day=$date->format('Y-m-d');
                $params = [
                    "P_ACTION_TYPE" =>$action_type,
                    "P_EMPLOYEE_OFFDAY_ID" =>'',
                    "P_OFFDAY_FROM" =>$off_day,
                    "P_OFFDAY_TO" => $off_day,
                    "P_TOTAL_OFFDAY" => '',
                    "P_EMPLOYEE_DUTY_ID" => $id,
                    "P_STATUS" => '',
                    "P_CREATED_BY" => auth()->id(),
                    "O_STATUS_CODE" => &$status_code,
                    "O_STATUS_MESSAGE" => &$status_message,
                ];
                DB::executeProcedure("MDA.CM_ENGINE.CM_EMPLOYEE_OFFDAYS_CUD", $params);
                if($params['O_STATUS_CODE']==='99'){
                    DB::rollback();
                    $response = [
                        "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                        "status_code" => $params['O_STATUS_CODE'],
                        "data" => $params,
                        "status_message" => $params['O_STATUS_MESSAGE']
                    ];
                    return $response;
                }
            }
            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function offDayData($request=[])
    {

        return EmployeeOffDay::where('employee_duty_id',$request->get('employee_duty_id'))
                        ->orderBy("created_date", 'desc')->get();
    }

    public function searchEmployeeDutySchedule($request = [])
    {
        $placement_type=$request->get('placement_type_id');
        $placement=$request->get('placement_id');
        $placement_vessel_id=$request->get('placement_vessel_id');
        $duty_year=$request->get('duty_year');
        $duty_month=$request->get('duty_month');

        if ($placement){
            $duties = EmployeeDuties::where('placement_id', '=',$placement )
                ->where('placement_type_id', '=', $placement_type)
                ->where('duty_year', '=', $duty_year)
                ->where('duty_month', '=', $duty_month)
                ->orderByDesc('employee_duty_id')
                ->get();
        }else{
            $duties = EmployeeDuties::where('placement_id', '=',$placement_vessel_id )
                ->where('placement_type_id', '=', $placement_type)
                ->where('duty_year', '=', $duty_year)
                ->where('duty_month', '=', $duty_month)
                ->orderByDesc('employee_duty_id')
                ->get();
        }

        $data=array();

        foreach ($duties as $val){
            $val->off_day=DB::table('MDA.CM_EMPLOYEE_OFFDAYS')
                ->select(DB::raw("LISTAGG(to_char(OFFDAY_FROM,'DD'), '-') WITHIN GROUP (ORDER BY OFFDAY_FROM) as off_day"))
            ->where('employee_duty_id',$val->employee_duty_id)
            ->value('off_day');
            $data[]=$val;
        }

        return $data;
    }

    public function getOffDayById($id){

        $off_days=EmployeeOffDay::where('employee_duty_id',$id)
            ->get();
        $off_day_data=array();
        foreach ($off_days as $item){
            $specificDate = strtotime($item->offday_from);
            $specificDate = date('d', $specificDate);
            $off_day_data[]=$specificDate;
        }
        return $off_day_data;
    }


}
