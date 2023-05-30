<?php

namespace App\Entities\Cms;

use App\Entities\Mda\Employee;
use App\Entities\Mea\Views\Designation;
use Illuminate\Database\Eloquent\Model;

class EmployeeDuties extends Model
{
    protected $table = 'MDA.CM_EMPLOYEE_DUTIES';
    protected $primaryKey = 'EMPLOYEE_DUTY_ID';

    protected $with=['placement','employee','designation','placement_type','vessel_placement'];
    public function placement(){
        return $this->belongsTo(LPlacement::class,'placement_id','placement_id');
    }
    public function vessel_placement(){
        return $this->belongsTo(CpaVessel::class,'placement_id','id');
    }

    public function employee(){
        return $this->belongsTo(Employee::class,'employee_id','emp_id');
    }

    public function designation(){
        return $this->belongsTo(Designation::class,'designation_id');
    }

    public function placement_type(){
        return $this->belongsTo(LPlacementType::class,'placement_type_id','placement_type_id');
    }



}
