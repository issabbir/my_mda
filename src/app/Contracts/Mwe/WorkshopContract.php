<?php
namespace App\Contracts\Mwe;


interface WorkshopContract
{
    public function storeWorkshopRequisition($request = []);
    public function workshopRequisitionAuthorized($request = []);
    public function workshopRequisitionCompete($request = []);
    public function RequisitionItemProcess($request = []);
    public function addWorkshopRequisitionItem($action_type = null,$request = [], $id=null);
    public function RequisitionDatatable();
    public function getWorkshopRequisition($id);
    public function getWorkshopRequisitionApproval($id);
    public function getWorkshopRequisitionItemByInspectionJob($id,$job_no=null);
    public function getMaintenanceReqDataByWorkshop($id,$workshopId);
    public function getMaintenanceReqAuthDataByWorkshop($id,$workshopId);
    public function getVesselInspectionData($id);
    public function searchProduct($searchTerm);

    public function workshopOrderDataTable($request = []);
}
