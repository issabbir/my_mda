<?php

namespace App\Entities\Mwe;

use App\Entities\Pmis\Employee\Employee;
use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    protected $table = 'MDA.MW_VESSELS';
    protected $primaryKey = 'id';


}
