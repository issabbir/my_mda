<?php


namespace App\Entities\Mda;


use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = "MDA.INVOICE";
    protected $primaryKey = "id";
}
