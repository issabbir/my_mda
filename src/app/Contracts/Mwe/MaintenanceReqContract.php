<?php
namespace App\Contracts\Mwe;


interface MaintenanceReqContract
{
    public function maintenanceCud($action_type=null, $request = [], $id=null);
    public function maintenanceDatatable();
    public function searchVessel($name);
    public function showVesselMaster($id);
    public function xenAuthorization($action_type=null,$request=[],$id=null);
    public function deputyChiefEngAuthorization($action_type=null,$request=[],$id=null);
    public function searchMaintenanceRequest($name);
}
