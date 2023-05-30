<?php


namespace App\Entities\Mda;


use Illuminate\Database\Eloquent\Model;

class TugsRegistration extends Model
{
    protected $table = "MDA.TUGS";
    protected $primaryKey = "id";

    public function tug_type(){
        return $this->belongsTo(LTugType::class, "tug_type_id", "id" );
    }

    public function live_tug_types()
    {
        return $this->tug_type()->where("status","!=", "D");
    }
}
