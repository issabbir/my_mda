<?php

namespace App\Entities\Cms;

use App\Entities\Pmis\Employee\Employee;
use App\Entities\Security\User;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Expression;

class FuelConsumptionMst extends Model
{
    protected $table = 'MDA.CM_FUEL_CONSUMPTION_MST';
    protected $primaryKey = 'FUEL_CONSUMPTION_MST_ID';

    protected $with=['vessel'];

    public function vessel(){
        return $this->belongsTo(CpaVessel::class,'cpa_vessel_id','id');
    }

    public function fuel_items(){
        return $this->hasMany(FuelConsumptionDtl::class,'fuel_consumption_mst_id','fuel_consumption_mst_id')
            ->orderBy('fuel_consumption_dlt_id');
    }

}
