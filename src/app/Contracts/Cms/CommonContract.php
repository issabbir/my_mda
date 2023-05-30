<?php
namespace App\Contracts\Cms;


interface CommonContract
{
    public function getCalenderMonths();
    public function getCalenderYear();
    public function getAllWorkFlowMaster();
    public function showWorkflowData($request=[]);
    public function showAuthorizedData($request=[]);
    public function showMappingData($id);
    public function getPlacementType();
    public function getPlacement();
    public function getPlacementVessel();
    public function getVessel();
    public function getOffDayList($year,$month);
    public function getLastConsumption($vessel_id);
    public function getDutyShiftByEmployeeDuty($year,$month);
    public function getFuelConsumptionNoByVessel($id);
    public function searchShiftingEmployee($name);
    public function getEmployee($id);
}
