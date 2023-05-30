<?php

namespace App\Entities\Mda;

use Illuminate\Database\Eloquent\Model;

class LVesselCondition extends Model
{
    protected $table = "MDA.L_VESSEL_CONDITIONS";
    protected $primaryKey = "id";

    public function pilotage_vessel_condition()
    {
        return $this->hasMany(PilotageVesselCondition::class, "vessel_condition_id","id");
    }

    public function pilotage()
    {
        return $this->belongsTo(Pilotage::class);
    }
}
