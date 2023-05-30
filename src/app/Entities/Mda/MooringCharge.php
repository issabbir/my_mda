<?php

namespace App\Entities\Mda;

use Illuminate\Database\Eloquent\Model;

class MooringCharge extends Model
{
    protected $table = "MDA.MOORING_CHARGE";
    protected $primaryKey = "MOORING_CHARGE_ID";

    public function local_vessel(){
        return $this -> belongsTo(LocalVessel::class,"local_vessel_id","id");
    }
    public function slip_type(){
        return $this -> belongsTo(LCollectionSlipType::class,"slip_type_id","id");
    }
}




