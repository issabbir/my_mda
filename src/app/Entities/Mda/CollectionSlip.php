<?php

namespace App\Entities\Mda;

use Illuminate\Database\Eloquent\Model;

class CollectionSlip extends Model
{
    protected $table = "MDA.COLLECTION_SLIPS";
    protected $primaryKey = "id";

    public function local_vessel(){
        return $this -> belongsTo(LocalVessel::class,"local_vessel_id","id");
    }
    public function slip_type(){
        return $this -> belongsTo(LCollectionSlipType::class,"slip_type_id","id");
    }
    public function office(){
        return $this -> belongsTo(LLicenseOffice::class,"office_id","office_id");
    }
}
