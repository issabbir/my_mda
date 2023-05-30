<?php

namespace App\Entities\Mwe;

use App\Entities\Mda\MediaFile;
use App\Entities\Pmis\Employee\Employee;
use App\Entities\Security\User;
use Illuminate\Database\Eloquent\Model;

class MaintenanceInspectionJob extends Model
{
    protected $table = 'MDA.MW_MAIN_INSPECTION_JOB_DTL';
    protected $primaryKey = 'job_dtl_id';
    protected  $with=[];

    public function inspection_job(){
        return $this->belongsTo(InspectionJob::class,'inspection_job_type_id','id');
    }

    public function inspection_creator(){
        return $this->belongsTo(User::class,'created_by','user_id')->with('employee');
    }

    public function maintenance_inspector()
    {
        return $this->belongsTo(MaintenanceInspector::class, 'inspector_job_id','inspector_job_id');
    }
}
