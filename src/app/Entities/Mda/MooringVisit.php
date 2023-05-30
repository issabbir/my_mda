<?php

namespace App\Entities\Mda;

use Illuminate\Database\Eloquent\Model;

class MooringVisit extends Model
{
    protected $table = 'MDA.MOORING_VISITS';
    protected $primaryKey = "id";
    protected $with=["employee"];

    public function cpa_vessel(){
       return $this -> belongsTo(CpaVessel::class,"cpa_vessel_id","id");
    }

    public function local_vessel(){
        return $this -> belongsTo(LocalVessel::class,"local_vessel_id","id");
    }

    public function swing_moorings(){
        return $this -> belongsTo(SwingMooring::class,"swing_mooring_id","id");
    }

    public function employee(){
           return $this->belongsTo(Employee::class, "lm_rep","emp_id");
    }
}




