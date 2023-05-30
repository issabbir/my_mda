<?php


namespace App\Entities\Cms;

use App\Entities\Pmis\Employee\Employee;
use Illuminate\Database\Eloquent\Model;

class WorkFlowMapping extends Model
{
    protected $table = 'MDA.WORKFLOW_MAPPING';
    protected $primaryKey = 'workflow_mapping_id';

   /* protected $casts = [
        'bill_date' => 'datetime:d/m/Y',
    ];*/
    protected  $with=[];


    public function workflow_master()
    {
        return $this->belongsTo(WorkFlowMaster::class,'workflow_master_id');
    }

    public function workflow_detail()
    {
        return $this->belongsTo(WorkFlowDetail::class,'workflow_detail_id','workflow_detail_id');
    }

    public function auth_related_emp(){
        return $this->belongsTo(Employee::class,'emp_id');
    }

    public function consumption(){
        return $this->belongsTo(FuelConsumptionMst::class,'reference_id','fuel_consumption_mst_id');
    }

}
