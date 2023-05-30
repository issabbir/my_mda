<?php

namespace App\Entities\Mwe;

use App\Entities\Pmis\Employee\Employee;
use Illuminate\Database\Eloquent\Model;

class WorkType extends Model
{
    protected $table = 'MDA.L_WORKING_TYPE';
    protected $primaryKey = 'WORK_ID';


}
