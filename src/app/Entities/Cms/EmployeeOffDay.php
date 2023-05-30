<?php

namespace App\Entities\Cms;

use Illuminate\Database\Eloquent\Model;

class EmployeeOffDay extends Model
{
    protected $table = 'MDA.CM_EMPLOYEE_OFFDAYS';
    protected $primaryKey = 'EMPLOYEE_OFFDAY_ID';
}
