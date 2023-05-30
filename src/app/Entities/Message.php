<?php


namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "MDA.message";
    protected $primaryKey = "message_id";
    protected $fillable = ['EMAIL_SEND_STATUS', 'SMS_SEND_STATUS', 'SMS_PROCESS_ID'];
    //public $timestamps = false;

}
