<?php

namespace App\Entities\Mwe;

use App\Entities\Pmis\Employee\Employee;
use Illuminate\Database\Eloquent\Model;

class MaintenanceInspector extends Model
{
    protected $table = 'MDA.MW_MAINTENANCE_INSPECTOR';
    protected $primaryKey = 'inspector_job_id';
    protected  $with=[];

    public function assigned_ssae(){
        return $this->belongsTo(Employee::class,'assigned_ssae_emp_id');
    }
    public function assigned_sae(){
        return $this->belongsTo(Employee::class,'assigned_sae_emp_id');
    }

}
