<?php

namespace App\Entities\Mwe;

use App\Entities\Pmis\Employee\Employee;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    protected $table = 'MDA.MW_WORKSHOPS';
    protected $primaryKey = 'id';

    protected $with=['authorization','workshop_saen'];

    public function authorization(){
        return $this->belongsTo(Employee::class,'in_charged_emp_id');
    }
    public function workshop_saen(){
        return $this->belongsTo(Employee::class,'saen_emp_id');
    }
}
