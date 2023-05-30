<?php


namespace App\Entities\Cms;

use Illuminate\Database\Eloquent\Model;

class WorkFlowDetail extends Model
{
    protected $table = 'MDA.WORKFLOW_DETAIL';
    protected $primaryKey = 'workflow_detail_id';

    public function workflow_master()
    {
        return $this->belongsTo(WorkFlowMaster::class,'workflow_master_id');
    }

    public function workflow_detail(){
        return $this->belongsTo(WorkFlowDetail::class,'workflow_detail_id');
    }

    public function workflow_history(){
        return $this->hasMany(WorkFlowMapping::class,'workflow_detail_id','workflow_detail_id')->orderBy('workflow_mapping_id','desc');
    }

}
