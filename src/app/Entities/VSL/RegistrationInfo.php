<?php


namespace App\Entities\VSL;

use App\Entities\Vtmis\VesselRegistration;
use App\Helpers\HelperClass;
use Illuminate\Database\Eloquent\Model;

class RegistrationInfo extends Model
{
    protected $table = 'VSL.VSL_REGISTRATION_INFO';
    protected $primaryKey = 'registration_id';

    protected $appends = ['FormattedRegistrationDate','FormattedArivalDate'];

    protected $casts = [
        'registration_date' => 'datetime:d/m/Y',
    ];

    public function getFormattedRegistrationDateAttribute()
    {
        return HelperClass::customDateFormat($this->registration_date);
    }

    public function getFormattedArivalDateAttribute()
    {
        return HelperClass::customDateFormat($this->arival_date);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class,'agency_id');
    }

    public function vesselReg()
    {
        return $this->belongsTo(VesselRegistration::class,'registration_no', 'reg_no');
    }
}
