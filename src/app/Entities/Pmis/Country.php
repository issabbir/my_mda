<?php

namespace App\Entities\Pmis;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'pmis.l_geo_country';
    protected $primaryKey = 'country_id';
}
