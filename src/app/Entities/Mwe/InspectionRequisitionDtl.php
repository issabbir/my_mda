<?php

namespace App\Entities\Mwe;

use Illuminate\Database\Eloquent\Model;

class InspectionRequisitionDtl extends Model
{
    protected $table = 'MDA.MW_INSPECTION_REQUISITION_DTLS';
    protected $primaryKey = 'id';

    protected $with=[];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
    public function unit(){
        return $this->belongsTo(Unit::class,'unit_id','id');
    }
}
