<?php

namespace App\Entities\Vtmis;

use App\Entities\Mda\VslJettyService;
use App\Entities\Pmis\Country;
use Illuminate\Database\Eloquent\Model;

class VesselRegistration extends Model
{
    protected $table = 'vtmis.vessel_registration';
    protected $primaryKey = 'reg_no';
    protected $keyType = 'string';
    protected $with = 'flag';

    public function flag()
    {
        return $this->belongsTo(Country::class, 'vessel_flag');
    }

}
