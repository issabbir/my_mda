<?php

/**
 * Created by PhpStorm.
 * User: Hossian
 * Date: 4/26/20
 * Time: 01:00 PM
 */

namespace App\Entities\Secdbms\Watchman;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'secdbms.wm_appointment';
    protected $primaryKey = 'appointment_id';
}
