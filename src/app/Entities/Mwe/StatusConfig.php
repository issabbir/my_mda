<?php

namespace App\Entities\Mwe;

use Illuminate\Database\Eloquent\Model;

class StatusConfig extends Model
{
    protected $table = 'MDA.MW_STATUS_CONFIG';
    protected $primaryKey = 'STATUS_CODE';
}
