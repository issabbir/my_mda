<?php

namespace App\Entities\Mea\Views;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table      = 'v_employee';
    protected $primaryKey = 'emp_id';

    /*protected $with = ['department', 'designation'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }*/
}

