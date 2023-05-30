<?php

namespace App\Contracts\Pmis\Employee;


interface EmployeeContract
{
    public function findEmployeeInformation($employeeId);
    public function searchEmployee($name);
    public function getEmployee($id);
    public function searchVesselMaster($name);
    public function getVesselMaster($id);
    public function searchMaintenanceSAEN($name);
    public function searchMaintenanceSSAEN($name);
    public function getMaintenanceSAEN($id);
    public function getMaintenanceSSAEN($id);
    public function getMaintenanceDeputyEngineer($id);
}
