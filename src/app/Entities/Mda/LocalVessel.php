<?php

namespace App\Entities\Mda;

use App\Entities\Secdbms\Watchman\LAgency;
use Illuminate\Database\Eloquent\Model;

class LocalVessel extends Model
{
    protected $table = "MDA.LOCAL_VESSELS";
    protected $primaryKey = "id";
    protected $keyType = "String";

    public function vessel_file()
    {
        return $this->hasOne(MediaFile::class,"ref_id","id");
    }

    public function agent()
    {
        return $this->belongsTo(LAgency::class, 'agency_id');
    }
}
