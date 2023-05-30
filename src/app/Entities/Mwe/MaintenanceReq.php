<?php

namespace App\Entities\Mwe;

use App\Entities\Mda\CpaVessel;
use App\Entities\Mda\MediaFile;
use App\Entities\Pmis\Employee\Employee;
use Illuminate\Database\Eloquent\Model;

class MaintenanceReq extends Model
{
    protected $table = 'MDA.MW_MAINTENANCE_REQS';
    protected $primaryKey = 'id';
    protected  $with=[];

    public function vessel(){
        //return $this->belongsTo(Vessel::class,'vessel_id');
        return $this->belongsTo(CpaVessel::class,'vessel_id','id');
    }
    public  function  department(){
        return $this->belongsTo(Department::class,'department_id');
    }
    public function vesselMaster(){
        return $this->belongsTo(Employee::class,'vessel_master_id');
    }
    public function assignedInspector(){
        return $this->belongsTo(Employee::class,'inspector_emp_id');
    }
    public function assignedBy(){
        return $this->belongsTo(Employee::class,'inspector_assigned_by_emp_id');
    }
    public function attachment()
    {
        return $this->belongsTo(MediaFile::class, 'id','ref_id');
    }
    public function slipwayName()
    {
        return $this->belongsTo(Slipway::class, 'slipway_id','id');
    }

}
