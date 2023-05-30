<?php

namespace App\Entities\Cms;

use App\Entities\Mda\Employee;
use Illuminate\Database\Eloquent\Model;

class CpaVessel extends Model
{

    protected $table = 'MDA.CPA_VESSELS';
    protected $primaryKey='id';
    protected $with=['vessel_type','fuel_type','employee'];

    public function vessel_type(){
        return $this->belongsTo(LVesselType::class,'vessel_type_id','id');
    }

    public function fuel_type(){
        return $this->belongsTo(LFuelType::class,'fuel_type_id','fuel_type_id');
    }

    public function employee(){
        return $this->belongsTo(Employee::class,'incharge_emp_id','emp_id');
    }
}
