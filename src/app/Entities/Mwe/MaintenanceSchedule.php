<?php

namespace App\Entities\Mwe;

use App\Entities\Mda\CpaVessel;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    protected $table = 'MDA.MW_MAINTENANCE_SCHEDULE';
    protected $primaryKey = 'id';

    public function  vessel()
    {
     return $this->belongsTo(Vessel::class,'vessel_id');
    }

    public function  department()
    {
        return $this->belongsTo(Department::class,'department_id');
    }
}
