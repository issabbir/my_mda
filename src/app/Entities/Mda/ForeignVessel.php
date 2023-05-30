<?php


namespace App\Entities\Mda;


use App\Entities\Secdbms\Watchman\LAgency;
use App\Entities\VSL\RegistrationInfo;
use Illuminate\Database\Eloquent\Model;

class ForeignVessel extends Model
{
    protected $table = "MDA.FOREIGN_VESSELS";
    protected $primaryKey = "id";
    protected $keyType='string';

    public function registration_info()
    {
        return $this->belongsTo(RegistrationInfo::class, 'reg_no',  'registration_no')->orderBy('update_date', 'asc');
//        return $this->belongsTo(RegistrationInfo::class, 'reg_no', 'registration_no')
//            ->orderByRaw('insert_date desc nulls last');
    }

    public function agency_info()
    {
        return $this->belongsTo(LAgency::class, 'shipping_agent_id');
    }





}
