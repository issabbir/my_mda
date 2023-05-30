<?php

namespace App\Entities\Mda;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Foreach_;

class BerthingSchedule extends Model
{
    protected $table = "MDA.BERTHING_SCHEDULE";
    protected $primaryKey = "id";

    public function local_vessel(){
        return $this -> belongsTo(ForeignVessel::class,"local_vessel_id");
    }
    public function slip_type(){
        return $this -> belongsTo(LCollectionSlipType::class,"slip_type_id");
    }

    public function foreign_vessel()
    {
        return $this->belongsTo(ForeignVessel::class, "vessel_id");
    }

    public function pilotage_type()
    {
        return $this->belongsTo(LPilotageType::class, "pilotage_type_id");
    }

    public function cpa_cargo()
    {
        return $this->belongsTo(CpaCargo::class, "curgo_id");
    }

    public function jetty()
    {
        return $this->belongsTo(AreaInfo::class, "jetty_id");
    }

    public function cpa_pilot(){
        return $this->belongsTo(CpaPilot::class, "pilot_id");
    }

}
