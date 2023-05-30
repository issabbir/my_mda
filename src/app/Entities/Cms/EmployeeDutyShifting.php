<?php

namespace App\Entities\Cms;

use Illuminate\Database\Eloquent\Model;

class EmployeeDutyShifting extends Model
{
    protected $table = 'MDA.CM_EMPLOYEE_DUTY_SHIFTING';
    protected $primaryKey = 'EMP_DUTY_SHIFTING_ID';

    protected $with=['shift'];
    public function shift(){
        return $this->belongsTo(Shifting::class,'shift_id','shifting_id');
    }
}
