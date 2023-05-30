<?php


namespace App\Entities\Mda;


use App\Entities\Mwe\Department;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = "PMIS.EMPLOYEE";
    protected $primaryKey = "emp_id";


}
