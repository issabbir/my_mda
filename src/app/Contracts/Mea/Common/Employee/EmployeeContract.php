<?php

namespace App\Contracts\Mea\Common\Employee;


interface EmployeeContract
{
    public function findEmployeeInformationWithAddress($employeeId);
    public function findEmployeeInformation($employeeId);
    public function findDeptWiseEmployeeCodesBy($searchTerm,$empDept);
    public function findDeptWiseStaffemployeesCodesBy($searchTerm,$empDept);
    public function findSupplierInformation($vehicleId);
    public function findworkTypeDetails($typeId);
    public function findFuelQuantity($depot_type,$fuel_consumption_type_id,$work_type_id,$engine_type_id,$qty_unit_id,$refuel_frequency_id,$fuel_type_id);
    public function findBulkFuelQuantity($fuelConsumptionTypeId, $fuelTypeId, $qtyUnitId, $refuelFrequencyId);
}
