<?php
namespace App\Contracts\Mwe;


interface SettingsContract
{
    public function slipwayCud($action_type=null, $request = [], $id=null);
    public function maintenanceScheduleCud($action_type=null, $request = [], $id=null);
    public function workshopCud($action_type=null, $request = [], $id=null);
    public function unitCud($action_type=null, $request = [], $id=null);
    public function inspectionJobCud($action_type=null, $request = [], $id=null);
    public function slipwayDatatable();
    public function maintenanceScheduleDatatable();
    public function workshopDatatable();
    public function unitDatatable();
    public function inspectionJobDatatable();
    public function getLastVesselMaintenanceDate($id);
    public function productDatatable();
    public function productCud($action_type=null, $request = [], $id=null);
}
