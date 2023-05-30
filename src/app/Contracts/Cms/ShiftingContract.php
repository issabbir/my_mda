<?php
namespace App\Contracts\Cms;


interface ShiftingContract
{
    public function dutiesStore($action_type=null, $request = [], $id=null);
    public function dutyShiftingStore($action_type=null, $request = [], $id=null);
    public function offDayStore($action_type=null, $request = [], $id=null);
    public function dutiesData();
    public function dutyShiftingData($request=[]);
    public function offDayData($request=[]);
    public function searchEmployeeDutySchedule($request=[]);
    public function getOffDayById($id);

}
