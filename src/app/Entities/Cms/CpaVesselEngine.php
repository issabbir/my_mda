<?php

namespace App\Entities\Cms;

use Illuminate\Database\Eloquent\Model;

class CpaVesselEngine extends Model
{
    protected $table = 'MDA.CM_CPA_VESSEL_ENGINE';
    protected $primaryKey = 'VESSEL_ENGINE_ID';

    protected $with=['engine','fuel_type'];

    public function engine(){
        return $this->belongsTo(LVesselEngineType::class,'engine_type_id','engine_id')->orderBy('engine_name');
    }

    public function fuel_type(){
        return $this->belongsTo(LFuelType::class,'fuel_type_id','fuel_type_id');
    }
}
