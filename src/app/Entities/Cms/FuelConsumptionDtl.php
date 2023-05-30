<?php

namespace App\Entities\Cms;

use Illuminate\Database\Eloquent\Model;

class FuelConsumptionDtl extends Model
{
    protected $table = 'MDA.CM_FUEL_CONSUMPTION_DTL';
    protected $primaryKey = 'FUEL_CONSUMPTION_DLT_ID';

    protected $with=['engine'];

    public function engine(){
        return $this->belongsTo(CpaVesselEngine::class,'vessel_engine_id','vessel_engine_id');
    }
}
