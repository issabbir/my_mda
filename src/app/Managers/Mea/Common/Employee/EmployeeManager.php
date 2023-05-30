<?php

namespace App\Managers\Mea\Common\Employee;


use App\Contracts\Mea\Common\Employee\EmployeeContract;
use App\Entities\Mea\Views\Employee;
use App\Entities\Mea\Views\LWorkType;
use App\Enums\Mea\Common\Employee\Statuses;
use App\Enums\YesNoFlag;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\DB;


class EmployeeManager implements EmployeeContract
{
    protected $employee;
    protected $auth;
    protected $lWorkType;

    public function __construct(Employee $employee, Guard $auth, LWorkType $lWorkType)
    {
        $this->employee = $employee;
        $this->auth = $auth;
        $this->lWorkType = $lWorkType;
    }

    public function populateEmployeeAddress($pkgFunction, $params)
    {
        $primaryId = $params[0];
        $addressType = $params[1];
        $querys = "SELECT " . $pkgFunction . "('" . $primaryId . "','" . $addressType . "') from dual";
        $address = DB::select($querys);
        if (isset($address[0]))
            return $address[0];

        return [];
    }

    public function populateFunctionInformation($pkgFunction, $params)
    {
        $primaryId = $params[0];
        $addressType = $params[1];
        $querys = "SELECT " . $pkgFunction . "('" . $primaryId . "','" . $addressType . "') from dual";
        $address = DB::select($querys);
        if (isset($address[0]))
            return $address[0];

        return [];
    }

    public function findDriverDetailsByVehicleId($vehicle_id, $option = null)
    {
        $queryDriver = <<<QUERY
           SELECT MEA.VM_DRIVER_ASSIGN_PKG.get_vehicle_wise_driver(:vehicle_id) FROM dual
QUERY;

        $assignedDriver = DB::selectOne($queryDriver, ['vehicle_id' => $vehicle_id]);
        if ($assignedDriver) {
            if ($option == 'a') {
                return $assignedDriver;
            } else {
                $jsonEncodedResult = json_encode($assignedDriver);
                $arrayResult = json_decode($jsonEncodedResult, true);
                return $arrayResult;
            }
        }
        return [];
    }

    public function findWorkshopsByWorkshopTypeId($workshopTypeId, $option = null)
    {
        $queryDriver = <<<QUERY
           SELECT WORKSHOP_ID as id,WORKSHOP_NAME as text FROM MEA.L_WORKSHOP W WHERE W.WORKSHOP_TYPE_ID = :workshopTypeId
QUERY;

        $workShops = DB::select($queryDriver, ['workshopTypeId' => $workshopTypeId]);
        if ($workShops) {
            if ($option == 'a') {
                return $workShops;
            } else {
                $jsonEncodedResult = json_encode($workShops);
                $arrayResult = json_decode($jsonEncodedResult, true);
                return $arrayResult;
            }
        }
        return [];
    }

    public function findVehiclesLastRefuelingInfo($vehicle_id)
    {
        $query = <<<QUERY
           SELECT MEA.VM_FUEL_PKG.get_last_fuel_rec(:vehicle_id) FROM dual
QUERY;

        $queryEngineType = <<<QUERY
            SELECT MEA.VM_FUEL_PKG.get_engine_type_rec(:vehicle_id) FROM dual
QUERY;

        $result = DB::selectOne($query, ['vehicle_id' => $vehicle_id]);
        $assignedDriver = $this->findDriverDetailsByVehicleId($vehicle_id, 'a');
        $resultEngineType = DB::selectOne($queryEngineType, ['vehicle_id' => $vehicle_id]);

        if (!isset($assignedDriver)) {
            $assignedDriver = array();
        }
        if (!isset($result)) {
            $result = array();
        }
        //if($result) {
        $jsonEncodedResult = json_encode(array($result, $assignedDriver,$resultEngineType));
        $arrayResult = json_decode($jsonEncodedResult, true);
        return $arrayResult;
        /*}
        return [];*/
    }

    public function findEmployeeInformation($employeeId)
    {
        $query = <<<QUERY

           SELECT  E.*,
                D.DESIGNATION,
                DPT.DEPARTMENT_NAME
           FROM V_EMPLOYEE e,
                V_DESIGNATION d,
                V_DEPARTMENT dpt
          WHERE  E.EMP_ID = :emp_id
                AND e.EMP_STATUS_ID = :emp_status_id
                AND E.DESIGNATION_ID = D.DESIGNATION_ID(+)
                AND E.DPT_DEPARTMENT_ID = DPT.DEPARTMENT_ID(+)

QUERY;

        $employee = DB::selectOne($query, ['emp_id' => $employeeId, 'emp_status_id' => Statuses::ON_ROLE]);

        if ($employee) {
            $jsonEncodedEmployee = json_encode($employee);
            $employeeArray = json_decode($jsonEncodedEmployee, true);
            return $employeeArray;
        }

        return [];
    }

    public function findEmployeeInformationWithAddress($employeeId)
    {
        $pkgFunction = 'MEA.VM_DRIVER_PKG.get_int_emp_address';
        $primaryId = $employeeId;
        $addressType = 1;
        $params = array($primaryId, $addressType);
        //permanent
        $presentAddress = $this->populateEmployeeAddress($pkgFunction, $params);

        $addressType = 2;
        $params = array($primaryId, $addressType);
        //present
        $permanentAddress = $this->populateEmployeeAddress($pkgFunction, $params);

        $query = <<<QUERY
        select * from v_employee
        WHERE
          emp_id = :emp_id
          AND EMP_STATUS_ID = :emp_status_id
QUERY;

        $employee = DB::selectOne($query, ['emp_id' => $employeeId, 'emp_status_id' => Statuses::ON_ROLE]);

        if ($employee) {
            $jsonEncodedEmployee = json_encode(array($employee, $presentAddress, $permanentAddress));
            $employeeArray = json_decode($jsonEncodedEmployee, true);

            return $employeeArray;
        }

        return [];
    }

    public function findDeptWiseEmployeeCodesBy($searchTerm, $empDept = null)
    {
        $empDeptArr = explode(',', $empDept);

        if (isset($empDept)) {   // department wise show employee code

            return $this->employee->where(
                [
                    ['emp_code', 'like', '' . $searchTerm . '%'],
                    ['emp_status_id', '=', Statuses::ON_ROLE],
                ]
            )->whereIn('dpt_department_id', $empDeptArr)->orWhere(
                [
                    ['emp_name', 'like', '' . $searchTerm . '%'],
                    ['emp_status_id', '=', Statuses::ON_ROLE],
                ]
            )->whereIn('dpt_department_id', $empDeptArr)->orderBy('emp_code', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name']);

        } else {  // to show all employee code

            return $this->employee->where(
                [
                    ['emp_code', 'like', '' . $searchTerm . '%'],
                    ['emp_status_id', '=', Statuses::ON_ROLE],
                ]
            )->orWhere(
                [
                    ['emp_name', 'like', '' . $searchTerm . '%'],
                    ['emp_status_id', '=', Statuses::ON_ROLE],
                ]
            )->orderBy('emp_code', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name']);

        }
    }


    public function findDeptWiseStaffemployeesCodesBy($searchTerm, $empDept)
    {
        $empDeptArr = explode(',', $empDept);
        if (isset($empDept)) {   // department wise show employee code

            return $this->employee->where(
                [
                    ['emp_code', 'like', '' . $searchTerm . '%'],
                    ['emp_status_id', '=', Statuses::ON_ROLE],
                    ['emp_type_id', '=', Statuses::EMP_TYPE_ID],
                ]
            )->whereIn('dpt_department_id', $empDeptArr)->orWhere(
                [
                    ['emp_name', 'like', '' . $searchTerm . '%'],
                    ['emp_status_id', '=', Statuses::ON_ROLE],
                    ['emp_type_id', '=', Statuses::EMP_TYPE_ID],
                ]
            )->whereIn('dpt_department_id', $empDeptArr)->orderBy('emp_code', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name']);

        } else {  // to show all employee code

            return $this->employee->where(
                [
                    ['emp_code', 'like', '' . $searchTerm . '%'],
                    ['emp_status_id', '=', Statuses::ON_ROLE],
                    ['emp_type_id', '=', Statuses::EMP_TYPE_ID],
                ]
            )->orWhere(
                [
                    ['emp_name', 'like', '' . $searchTerm . '%'],
                    ['emp_status_id', '=', Statuses::ON_ROLE],
                    ['emp_type_id', '=', Statuses::EMP_TYPE_ID],
                ]
            )->orderBy('emp_code', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name']);
        }
    }

    public function findSupplierInformation($vehicleId)
    {
        $query = <<<QUERY

 SELECT LVS.V_SUPPLIER_NAME, LVS.V_SUPPLIER_ADDRESS, TO_CHAR(LVS.CONTACT_START_DT,'DD-MM-YYYY') CONTACT_START_DT
  FROM L_VEHICLE_SUPPLIER LVS, VM_VEHICLE_INFO SVI
 WHERE     LVS.V_SUPPLIER_ID = SVI.V_SUPPLIER_ID
       AND SVI.VEHICLE_ID = :vehicle_Id
QUERY;

        $supplier = DB::selectOne($query, ['vehicle_Id' => $vehicleId]);

        if ($supplier) {
            $jsonEncodedSupplier = json_encode($supplier);
            $supplierArray = json_decode($jsonEncodedSupplier, true);
            return $supplierArray;
        }

        return [];
    }

    public function findworkTypeDetails($typeId = null)
    {
        if (isset($typeId)) {   // with out 5,  show all WORK_TYPE

            return $this->lWorkType->where(
                [
                    ['active_yn', '=', YesNoFlag::YES],
                    ['work_type_id', '!=', '' . $typeId],
                ]
            )->orderBy('work_type', 'ASC')->limit(10)->get(['work_type_id', 'work_type']);

        } else {  // to show all WORK_TYPE

            return $this->lWorkType->where(
                [
                    ['active_yn', '=', YesNoFlag::YES],

                ]
            )->orderBy('work_type', 'ASC')->limit(10)->get(['work_type_id', 'work_type']);

        }
    }

    public function findFuelQuantity($depot_type, $fuel_consumption_type_id, $work_type_id, $engine_type_id, $qty_unit_id, $refuel_frequency_id, $fuel_type_id)
    {
        try {
            if ($depot_type == 'Y') {
                if ($fuel_consumption_type_id == '1') {
                    $query = "SELECT QTY
            FROM MEA.FUEL_LIMIT
            WHERE ACTIVE_TO IS NULL
            AND (WORK_TYPE_ID = $work_type_id OR $work_type_id IS NULL)
            AND (MAIN_FUEL_ID = $fuel_type_id OR $fuel_type_id IS NULL)
            AND (QTY_UNIT_ID = $qty_unit_id OR $qty_unit_id IS NULL)
            AND (REFUEL_FREQUENCY_ID = $refuel_frequency_id OR $refuel_frequency_id IS NULL)
            AND (ENGINE_TYPE_ID = $engine_type_id OR $engine_type_id IS NULL) ";

            } else if ($fuel_consumption_type_id == '2') {
                    $query = " SELECT QTY
            FROM MEA.FUEL_LIMIT_ADDITION
            WHERE ACTIVE_TO IS NULL
          AND (WORK_TYPE_ID = $work_type_id OR $work_type_id IS NULL)
          AND (QTY_UNIT_ID = $qty_unit_id OR $qty_unit_id IS NULL)
          AND REFUEL_FREQUENCY_ID = $refuel_frequency_id
          AND (ENGINE_TYPE_ID = $engine_type_id OR $engine_type_id IS NULL) ";}

                return DB::select(DB::raw($query));
            }

        } catch (\Exception $e) {
            return false;
        }
    }

    public function findBulkFuelQuantity($fuelConsumptionTypeId, $fuelTypeId, $qtyUnitId, $refuelFrequencyId){
        try {
                if ($fuelConsumptionTypeId == '1') {
                    $query = "SELECT QTY
            FROM MEA.FUEL_LIMIT
            WHERE ACTIVE_TO IS NULL
            AND (MAIN_FUEL_ID = $fuelTypeId OR $fuelTypeId IS NULL)
            AND (QTY_UNIT_ID = $qtyUnitId OR $qtyUnitId IS NULL)
            AND (REFUEL_FREQUENCY_ID = $refuelFrequencyId OR $refuelFrequencyId IS NULL)";

                } else if ($fuelConsumptionTypeId == '2') {
                    $query = " SELECT QTY
            FROM MEA.FUEL_LIMIT_ADDITION
            WHERE ACTIVE_TO IS NULL
          AND (QTY_UNIT_ID = $qtyUnitId OR $qtyUnitId IS NULL)
          AND REFUEL_FREQUENCY_ID = $refuelFrequencyId ";}

                return DB::select(DB::raw($query));

        } catch (\Exception $e) {
            return false;
        }
    }

}
