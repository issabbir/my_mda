<?php

namespace App\Entities\Mwe;

use App\Entities\Mda\Employee;
use Illuminate\Database\Eloquent\Model;

class VesselInspection extends Model
{
    protected $table = 'MDA.MW_VESSEL_INSPECTIONS';
    protected $primaryKey = 'ID';
    protected  $with=[];

    public function inspection_job(){
        return $this->belongsTo(InspectionJob::class,'inspection_job_id');
    }
    public function workshop(){
        return $this->belongsTo(Workshop::class,'workshop_id');
    }

    public function ssaen_info(){
        return $this->belongsTo(Employee::class,'ssaen_emp_id','emp_id');
    }

    public function saen_info(){
        return $this->belongsTo(Employee::class,'saen_emp_id','emp_id');
    }

    public function maintenance_inspector(){
        return $this->belongsTo(MaintenanceInspector::class,'maintenance_req_id','maintenance_req_id');
    }
}
