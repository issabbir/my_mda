<?php

namespace App\Entities\Mwe;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'MDA.MW_DEPARTMENTS';
    protected $primaryKey = 'id';
}
