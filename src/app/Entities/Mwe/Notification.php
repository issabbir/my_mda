<?php

namespace App\Entities\Mwe;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'MDA.MW_NOTIFICATIONS';
    protected $primaryKey = 'id';
    protected $fillable = ['STATUS', 'SMS_PROCESS_ID'];
}
