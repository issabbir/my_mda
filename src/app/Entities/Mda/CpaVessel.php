<?php

namespace App\Entities\Mda;

use Illuminate\Database\Eloquent\Model;

class CpaVessel extends Model
{
    protected $table = "MDA.CPA_VESSELS";
    protected $primaryKey = "ID";

    public function vessel_type(){
        return $this->belongsTo(LCpaVesselType::class, "vessel_type_id", "id" );
    }
}
