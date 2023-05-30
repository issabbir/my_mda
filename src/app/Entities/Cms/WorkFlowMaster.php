<?php


namespace App\Entities\Cms;

use Illuminate\Database\Eloquent\Model;

class WorkFlowMaster extends Model
{
    protected $table = 'MDA.WORKFLOW_MASTER';
    protected $primaryKey = 'workflow_master_id';

    protected $casts = [
        'bill_date' => 'datetime:d/m/Y',
    ];

    public function l_bill_type()
    {
        return $this->belongsTo(BillType::class,'bill_type_id');
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class,'agency_id');
    }

    public function bill_details(){
        return $this->hasMany('App\Entities\Vsl\BillDetails','bill_mst_id', 'bill_mst_id');
    }
}
