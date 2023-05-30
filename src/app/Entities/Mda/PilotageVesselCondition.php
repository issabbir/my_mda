<?php


namespace App\Entities\Mda;


use Illuminate\Database\Eloquent\Model;

class PilotageVesselCondition extends Model
{
    protected $table = "MDA.PILOTAGE_VESSEL_CONDITIONS";
    protected $primaryKey = "id";
    protected $with=["vessel_condition"];
    public function vessel_condition(){
        return $this->belongsTo(LVesselCondition::class,"vessel_condition_id","id");
    }
}
