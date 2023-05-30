<?php
namespace App\Contracts\Mwe;


interface InspectionReqContract
{
    public function storeInspectionReport($action_type=null,$request = [], $id=null);
    public function workshopAssign($request = [], $id=null);
    public function inspectionDatatable();
    public function getInspection($id);
    public function getVesselInspection($id);
    public function maintenanceInspectionJob($action_type=null,$request = [],$id=null);
    public function maintenanceVesselInspector($action_type=null,$request = [],$id=null);
    public function getMaintenanceInspector($id);
    public function getInspectionOrderDataTable();
    public function getInspectionOrderJob($id);
    public function storeInspectionJob($action_type=null,$request = [],$id=null);
    public function getMaintenanceInspection($id);
    public function getInspectionByInspectorJob($id);
}
