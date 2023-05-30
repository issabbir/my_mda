<?php


namespace App\Entities\Mda;


use Illuminate\Database\Eloquent\Model;

class Pilotage extends Model
{
    protected $table = "MDA.PILOTAGES";
    protected $primaryKey = "id";
    protected $appends = ['invoice'];

    public function working_type(){
        return $this->belongsTo(LVesselWorkingType::class, "working_type_id","id");
    }

    public function pilotage_type(){
        return $this->belongsTo(LPilotageType::class, "pilotage_type_id","id");
    }

    public function schedule_type(){
        return $this->belongsTo(LPilotageScheduleType::class, "schedule_type_id","id");
    }

    public function work_location(){
        return $this->belongsTo(LPilotageWorkLocation::class, "work_location_id","id");
    }

    public function cpa_pilot(){
        return $this->belongsTo(CpaPilot::class, "pilot_id","id")->where("status","=","A");
    }

    public function foreign_vessel(){
        return $this->belongsTo(ForeignVessel::class, "vessel_id","id");
    }

    public function mother_vessel()
    {
        return $this->belongsTo(ForeignVessel::class, "mother_vessel_id","id")->where("status","=", "A");
    }

    public function pilotage_vessel_condition()
    {
        return $this->hasMany(PilotageVesselCondition::class);
    }

    public function pilotage_tug()
    {
        return $this->hasMany(PilotageTug::class)->where("status","=", "A")->with("tugs","work_location");
    }

    public function pilotage_files()
    {
        return $this->hasMany(MediaFile::class,"ref_id")->where("status","=", "A");
    }

    public function getInvoiceAttribute() {
        $invoice = Invoice::select('invoice_no')->where('TRACE_ID', $this->id)->first();
        return ($invoice)?$invoice->invoice_no:'';
    }
}
