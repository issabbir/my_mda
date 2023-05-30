<?php


namespace App\Entities\Mda;


use Illuminate\Database\Eloquent\Model;

class PilotageTug extends Model
{
    protected $table="MDA.pilotage_tugs";
    protected $primaryKey="id";

    public function tugs()
    {
        return $this->belongsTo(TugsRegistration::class, "tug_id","id");
    }

    public function work_location(){
        return $this->belongsTo(LPilotageWorkLocation::class, "work_location_id");
    }
}
