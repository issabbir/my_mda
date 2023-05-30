<?php
namespace App\Managers\Mwe;

use Apiz\Http\Request;
use App\Contracts\Mwe\MaintenanceReqContract;
use App\Contracts\Mwe\WorkshopContract;
use App\Entities\Mwe\InspectionJob;
use App\Entities\Mwe\InspectionRequisitionDtl;
use App\Entities\Mwe\MaintenanceReq;
use App\Entities\Mwe\Product;
use App\Entities\Mwe\VesselInspection;
use App\Helpers\HelperClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\DeclareDeclare;

class WorkshopManager implements WorkshopContract
{
    public function storeWorkshopRequisition( $request = [])
    {//dd($request->all());
        $getKey = json_encode(Auth::user()->roles->pluck('role_key'));//dd($getKey);

        if(strpos($getKey, "MDA_SAE_M") == TRUE){
            $forward_to = 'MDA_SAE_M';
        }else if (strpos($getKey, "MDA_SSAE_M") == TRUE){
            $forward_to = 'MDA_SSAE_M';
        }else if (strpos($getKey, "MDA_ASW") == TRUE){
            $forward_to = 'MDA_ASW';
        }else if (strpos($getKey, "MDA_XEN") == TRUE){
            $forward_to = 'MDA_XEN';
        }
        $vessel_inspection_id=$request->get('vessel_inspection_id');
        $remarks_ssae=$request->get('remarks_ssae');
        $remarks_asw=$request->get('remarks_asw');

        $item_response=$this->RequisitionItemProcess($request);
        if($item_response['status_code']==='1' && in_array($request->get('req_status'),['P','I','R'])){//dd('1');
            try {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params = [
                    "P_ID" => $request->get('vessel_inspection_id'),
                    "P_JOB_NUMBER"  => $request->get('job_number'),
                    "P_MAINTENANCE_REQ_ID"  => $request->get('maintenance_req_id'),
                    "P_REQUISITION_NUMBER" =>$request->get('requisition_number'),
                    "P_TASK_DETAILS" => $request->get('task_details'),
                    "P_DESCRIPTION" => '',
                    "P_REMARKS" => $request->get('remarks'),
                    "P_STATUS" => 'I',
                    "P_FORWARD_TO" => $request->get('forward_to'),
                    "P_FORWARD_ROLE" => $forward_to,
                    "P_REMARKS_SSAE" => isset($remarks_ssae) ? $remarks_ssae : '',
                    "P_REMARKS_ASW" => isset($remarks_asw) ? $remarks_asw : '',
                    "P_UPDATED_BY" =>auth()->id(),
                    "O_STATUS_CODE" => &$status_code,
                    "O_STATUS_MESSAGE" => &$status_message,
                ];//dd($params);
                DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_WORKSHOP_REQ_PROCESS", $params);
                $response = [
                    "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                    "status_code" =>  $params['O_STATUS_CODE'],
                    "vessel_inspection_id" => $vessel_inspection_id,
                    "requisition_status" => HelperClass::getStatusByInspectionId($vessel_inspection_id),
                    "data" => $params,
                    "status_message" => $params['O_STATUS_MESSAGE']
                ];
            } catch (\Exception $e) {
                $response = ["status" => false, "status_code" => 99,  "vessel_inspection_id" => $vessel_inspection_id, "status_message" => 'Please try again later.'];
            }
            return $response;
        }else if(strpos($getKey, "MDA_XEN") == FALSE){
            if(strpos($getKey, "MDA_SAE_M") == TRUE){
                return $item_response;
            }
            try {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params = [
                    "P_ID" => $request->get('vessel_inspection_id'),
                    "P_JOB_NUMBER"  => $request->get('job_number'),
                    "P_MAINTENANCE_REQ_ID"  => $request->get('maintenance_req_id'),
                    "P_REQUISITION_NUMBER" =>$request->get('requisition_number'),
                    "P_TASK_DETAILS" => $request->get('task_details'),
                    "P_DESCRIPTION" => '',
                    "P_REMARKS" => $request->get('remarks'),
                    "P_STATUS" => 'I',
                    "P_FORWARD_TO" => $request->get('forward_to'),
                    "P_FORWARD_ROLE" => $forward_to,
                    "P_REMARKS_SSAE" => isset($remarks_ssae) ? $remarks_ssae : '',
                    "P_REMARKS_ASW" => isset($remarks_asw) ? $remarks_asw : '',
                    "P_UPDATED_BY" =>auth()->id(),
                    "O_STATUS_CODE" => &$status_code,
                    "O_STATUS_MESSAGE" => &$status_message,
                ];//dd($params);
                DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_WORKSHOP_REQ_PROCESS", $params);
                $response = [
                    "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                    "status_code" =>  $params['O_STATUS_CODE'],
                    "vessel_inspection_id" => $vessel_inspection_id,
                    "requisition_status" => HelperClass::getStatusByInspectionId($vessel_inspection_id),
                    "data" => $params,
                    "status_message" => $params['O_STATUS_MESSAGE']
                ];
            } catch (\Exception $e) {
                $response = ["status" => false, "status_code" => 99,  "vessel_inspection_id" => $vessel_inspection_id, "status_message" => 'Please try again later.'];
            }
            return $response;
        }else{
            return $item_response;
        }

    }

    public function workshopRequisitionAuthorized( $request = [])
    {//dd($request->all());
        $getKey = json_encode(Auth::user()->roles->pluck('role_key'));//dd($getKey);

        if(strpos($getKey, "MDA_SAE_M") == TRUE){
            $forward_to = 'MDA_SAE_M';
        }else if (strpos($getKey, "MDA_SSAE_M") == TRUE){
            $forward_to = 'MDA_SSAE_M';
        }else if (strpos($getKey, "MDA_ASW") == TRUE){
            $forward_to = 'MDA_ASW';
        }else if (strpos($getKey, "MDA_XEN") == TRUE){
            $forward_to = 'MDA_XEN';
        }
        $item_response=$this->RequisitionItemProcess($request);
        $vessel_inspection_id=$request->get('vessel_inspection_id');
        if($item_response['status_code']==='1'){
            try {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params = [
                    "P_ID" => $request->get('vessel_inspection_id'),
                    "P_JOB_NUMBER"  => $request->get('job_number'),
                    "P_MAINTENANCE_REQ_ID"  => $request->get('maintenance_req_id'),
                    "P_REQUISITION_NUMBER" =>$request->get('requisition_number'),
                    "P_TASK_DETAILS" => $request->get('task_details'),
                    "P_DESCRIPTION" => '',
                    "P_REMARKS" => $request->get('remarks'),
                    "P_APPROVER_EMP_ID" => auth()->user()->employee->emp_id,
                    "P_STATUS" => $request->get('action_status'),
                    "P_FORWARD_TO" => $request->get('forward_to'),
                    "P_FORWARD_ROLE" => $forward_to,
                    "P_UPDATED_BY" =>auth()->id(),
                    "O_STATUS_CODE" => &$status_code,
                    "O_STATUS_MESSAGE" => &$status_message,
                ];//dd($params);
                DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_WORKSHOP_REQ_PROCESS_AUTH", $params);
                $response = [
                    "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                    "status_code" =>  $params['O_STATUS_CODE'],
                    "vessel_inspection_id" =>  $vessel_inspection_id,
                    "requisition_status" => HelperClass::getStatusByInspectionId($vessel_inspection_id),
                    "data" => $params,
                    "status_message" => $params['O_STATUS_MESSAGE']
                ];
            } catch (\Exception $e) {
                $response = ["status" => false, "status_code" => 99, "vessel_inspection_id" =>  $vessel_inspection_id, "status_message" => 'Please try again later.'];
            }
            return $response;
        }else{
            return $item_response;
        }
    }

    public function workshopRequisitionCompete( $request = [])
    {
        $vessel_inspection_id=$request->get('vessel_inspection_id');
        $item_response=$this->RequisitionItemProcess($request);
        if($item_response['status_code']==='1'){
            try {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params = [
                    "P_ID" => $request->get('vessel_inspection_id'),
                    "P_JOB_NUMBER"  => $request->get('job_number'),
                    "P_MAINTENANCE_REQ_ID"  => $request->get('maintenance_req_id'),
                    "P_REQUISITION_NUMBER" =>$request->get('requisition_number'),
                    "P_TASK_DETAILS" => $request->get('task_details'),
                    "P_DESCRIPTION" => '',
                    "P_REMARKS" => $request->get('remarks'),
                    "P_STATUS" => $request->get('action_status'),
                    "P_FORWARD_TO" => '',
                    "P_FORWARD_ROLE" => '',
                    "P_REMARKS_SSAE" => '',
                    "P_REMARKS_ASW" => '',
                    "P_UPDATED_BY" =>auth()->id(),
                    "O_STATUS_CODE" => &$status_code,
                    "O_STATUS_MESSAGE" => &$status_message,
                ];//dd($params);
                DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_WORKSHOP_REQ_PROCESS", $params);
                $response = [
                    "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                    "status_code" =>  $params['O_STATUS_CODE'],
                    "vessel_inspection_id" =>  $vessel_inspection_id,
                    "requisition_status" => HelperClass::getStatusByInspectionId($vessel_inspection_id),
                    "data" => $params,
                    "status_message" => $params['O_STATUS_MESSAGE']
                ];
            } catch (\Exception $e) {
                $response = ["status" => false, "status_code" => 99, "vessel_inspection_id" =>  $vessel_inspection_id, "status_message" => 'Please try again later.'];
            }
            return $response;
        }else{
            return $item_response;
        }

    }

    public  function RequisitionItemProcess($request=[]){
        $items=$request->get('req_items');
        $vessel_inspection_id=$request->get('vessel_inspection_id');
            DB::beginTransaction();
            try {
                if($items){
                    foreach ($items as $value) {
                        $vessel_inspection_id=$request->get('vessel_inspection_id');
                        $status_code = sprintf("%4000s", "");
                        $status_message = sprintf("%4000s", "");
                        $params = [
                            "P_ACTION_TYPE" => 'U',
                            "P_ID" => $value['req_item_id'],
                            "P_REQUISITION_ID" => $value['requisition_id'],
                            "P_PRODUCT_ID" => $value['product_id'],
                            "P_DEMAND_QTY" => $value['demand_qty'],
                            "P_USED_QTY" => isset($value['used_qty'])?$value['used_qty']:'',
                            "P_OLD_RETURN_QTY" => isset($value['old_return_qty'])?$value['old_return_qty']:'',
                            "P_UNIT_ID" => $value['unit_id'],
                            "P_COLLECTED_QTY" => isset($value['collected_qty'])?$value['collected_qty']:'',
                            "P_RECEIVED_QTY" => isset($value['received_qty'])?$value['received_qty']:'',
                            "P_VESSEL_INSPECTION_ID" => $value['vessel_inspection_id'],
                            "P_UPDATED_BY" => auth()->id(),
                            "P_STATUS" =>isset($value['status'])?$value['status']:'P',
                            "O_STATUS_CODE" => &$status_code,
                            "O_STATUS_MESSAGE" => &$status_message,
                        ];
                        DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_REQUISITION_ITEM_PROCESS", $params);
                        if($params['O_STATUS_CODE']==='99'){
                            DB::rollback();
                            $response = [
                                "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                                "status_code" => $params['O_STATUS_CODE'],
                                "vessel_inspection_id" =>  $vessel_inspection_id,
                                "data" => $params,
                                "status_message" => $params['O_STATUS_MESSAGE']
                            ];
                            return $response;
                        }
                    }
                }
                $response = [
                    "status" => ($params['O_STATUS_CODE'] === '1') ? true : false,
                    "status_code" => $params['O_STATUS_CODE'],
                    "vessel_inspection_id" =>  $vessel_inspection_id,
                    "data" => $params,
                    "status_message" => $params['O_STATUS_MESSAGE']
                ];
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $response = ["status" => false, "status_code" => 99,  "vessel_inspection_id" =>  $vessel_inspection_id, "status_message" => 'Please try again later.'];
            }
            return $response;
    }

    public function addWorkshopRequisitionItem($action_type = null,$request = [], $id = null)
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "P_ACTION_TYPE" => $action_type,
                "P_ID"  => [ "value" => &$id, "type" => \PDO::PARAM_INPUT_OUTPUT, "length" => 255 ],
                "P_REQUISITION_ID" => '',
                "P_VESSEL_INSPECTION_ID" => $request->get("vessel_inspection_id"),
                "P_PRODUCT_ID" => $request->get("product_id"),
                "P_DEMAND_QTY" => $request->get("demand_qty"),
                "P_UNIT_ID" => $request->get("unit_id"),
                "P_CREATED_BY" =>auth()->id(),
                "O_STATUS_CODE" => &$status_code,
                "O_STATUS_MESSAGE" => &$status_message,
            ];

            DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_WORKSHOP_REQ_ITEM_CD", $params);
            $response = [
                "status" => ($params['O_STATUS_CODE'] ==='1')?true:false,
                "status_code" =>  $params['O_STATUS_CODE'],
                "data" => $params,
                "status_message" => $params['O_STATUS_MESSAGE']
            ];
        } catch (\Exception $e) {
            $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
        }
        return $response;
    }

    public function RequisitionAuthDatatable()
    {
        $role_key = json_decode(Auth::user()->roles->pluck('role_key'));//dd($role_key);

        /*if ((Auth::user()->hasPermission('CAN_XEN_MDA')) == 'true') {
            return MaintenanceReq::whereIN('status', ['9', '10', '11'])
                ->with('vessel', 'department', 'vesselMaster', 'assignedInspector')
                ->orderBy("created_at", 'desc')->get();
        }else*/ if (in_array("MDA_ASW", $role_key)) {
            /*return MaintenanceReq::whereIN('status', ['9', '10', '11'])
                ->where('forward_to_asw', auth()->user()->emp_id)
                ->with('vessel', 'department', 'vesselMaster', 'assignedInspector')
                ->orderBy("created_at", 'desc')->get();*/
            $querys = "SELECT DISTINCT mr.REQUEST_NUMBER,
                  mr.CREATED_AT,
                  mr.ID,
                  dep.DEPARTMENT_NAME     department,
                  vess.NAME               vessel,
                  inch.EMP_NAME           vessel_master,
                  inspect.EMP_NAME        assigned_inspector,
                  assindby.emp_name       inspector_assigned_by_emp_id,
                  mr.STATUS
    FROM MDA.MW_MAINTENANCE_REQS mr
         LEFT JOIN PMIS.L_DEPARTMENT dep
             ON dep.DEPARTMENT_ID = mr.DEPARTMENT_ID
         LEFT JOIN MDA.CPA_VESSELS vess ON vess.ID = mr.VESSEL_ID
         LEFT JOIN PMIS.EMPLOYEE inch ON inch.EMP_ID = vess.INCHARGE_EMP_ID
         LEFT JOIN PMIS.EMPLOYEE inspect
             ON inspect.EMP_ID = mr.inspector_emp_id
         LEFT JOIN PMIS.EMPLOYEE assindby
             ON assindby.EMP_ID = mr.inspector_assigned_by_emp_id
         LEFT JOIN MDA.MW_VESSEL_INSPECTIONS vi
             ON vi.MAINTENANCE_REQ_ID = mr.ID
   WHERE mr.STATUS IN (9, 10, 11) AND vi.FORWARD_TO_ASW = :FORWARD_TO_ASW
ORDER BY mr.CREATED_AT DESC" ;
            $data = db::select($querys,['FORWARD_TO_ASW' => auth()->user()->emp_id]);
            return $data;
        }else if (in_array("MDA_SSAE_M", $role_key)){//dd($role_key);
            $querys = "SELECT DISTINCT mr.REQUEST_NUMBER,
                  mr.CREATED_AT,
                  mr.ID,
                  dep.DEPARTMENT_NAME     department,
                  vess.NAME               vessel,
                  inch.EMP_NAME           vessel_master,
                  inspect.EMP_NAME        assigned_inspector,
                  assindby.emp_name       inspector_assigned_by_emp_id,
                  mr.STATUS
    FROM MDA.MW_MAINTENANCE_REQS mr
         LEFT JOIN PMIS.L_DEPARTMENT dep
             ON dep.DEPARTMENT_ID = mr.DEPARTMENT_ID
         LEFT JOIN MDA.CPA_VESSELS vess ON vess.ID = mr.VESSEL_ID
         LEFT JOIN PMIS.EMPLOYEE inch ON inch.EMP_ID = vess.INCHARGE_EMP_ID
         LEFT JOIN PMIS.EMPLOYEE inspect
             ON inspect.EMP_ID = mr.inspector_emp_id
         LEFT JOIN PMIS.EMPLOYEE assindby
             ON assindby.EMP_ID = mr.inspector_assigned_by_emp_id
         LEFT JOIN MDA.MW_VESSEL_INSPECTIONS vi
             ON vi.MAINTENANCE_REQ_ID = mr.ID
   WHERE mr.STATUS IN (9, 10, 11) AND vi.FORWARD_TO_SSAE = :FORWARD_TO_SSAE
ORDER BY mr.CREATED_AT DESC" ;
            $data = db::select($querys,['FORWARD_TO_SSAE' => auth()->user()->emp_id]);
            return $data;
        }else if (in_array("MDA_XEN", $role_key)){
            $querys = "SELECT DISTINCT mr.REQUEST_NUMBER,
                  mr.CREATED_AT,
                  mr.ID,
                  dep.DEPARTMENT_NAME     department,
                  vess.NAME               vessel,
                  inch.EMP_NAME           vessel_master,
                  inspect.EMP_NAME        assigned_inspector,
                  assindby.emp_name       inspector_assigned_by_emp_id,
                  mr.STATUS
    FROM MDA.MW_MAINTENANCE_REQS mr
         LEFT JOIN PMIS.L_DEPARTMENT dep
             ON dep.DEPARTMENT_ID = mr.DEPARTMENT_ID
         LEFT JOIN MDA.CPA_VESSELS vess ON vess.ID = mr.VESSEL_ID
         LEFT JOIN PMIS.EMPLOYEE inch ON inch.EMP_ID = vess.INCHARGE_EMP_ID
         LEFT JOIN PMIS.EMPLOYEE inspect
             ON inspect.EMP_ID = mr.inspector_emp_id
         LEFT JOIN PMIS.EMPLOYEE assindby
             ON assindby.EMP_ID = mr.inspector_assigned_by_emp_id
         LEFT JOIN MDA.MW_VESSEL_INSPECTIONS vi
             ON vi.MAINTENANCE_REQ_ID = mr.ID
   WHERE mr.STATUS IN (9, 10, 11) AND vi.FORWARD_TO_XEN = :FORWARD_TO_XEN
ORDER BY mr.CREATED_AT DESC" ;
            $data = db::select($querys,['FORWARD_TO_XEN' => auth()->user()->emp_id]);
            return $data;
        }
        else if (in_array("MDA_SAE_M", $role_key)){//dd($role_key);
            return MaintenanceReq::whereIN('status', ['9', '10', '11'])
                ->where('forward_to_ssae', auth()->user()->emp_id)
                ->with('vessel', 'department', 'vesselMaster', 'assignedInspector')
                ->orderBy("created_at", 'desc')->get();
        } else {
            return MaintenanceReq::whereIN('status', ['9', '10', '11'])
                //->where('sae_mechanical_emp_id', auth()->user()->emp_id)
                ->with('vessel', 'department', 'vesselMaster', 'assignedInspector')
                ->orderBy("created_at", 'desc')->get();
        }
    }

    public function RequisitionDatatable()
    {
        /*return MaintenanceReq::whereIN('status', ['9', '10', '11'])
            ->leftJoin('MDA.MW_VESSEL_INSPECTIONS','MDA.MW_VESSEL_INSPECTIONS.MAINTENANCE_REQ_ID','=','MDA.MW_MAINTENANCE_REQS.id')
            ->leftJoin('MDA.MW_WORKSHOPS','MDA.MW_VESSEL_INSPECTIONS.WORKSHOP_ID','=','MDA.MW_WORKSHOPS.id')
            ->where('MDA.MW_WORKSHOPS.SAEN_EMP_ID', auth()->user()->emp_id)
            ->with('vessel', 'department', 'vesselMaster', 'assignedInspector')
            ->orderBy("created_at", 'desc')->get();*/
        $querys = "select distinct mr.*, dep.DEPARTMENT_NAME, vess.NAME, vess_mst.EMP_NAME vessel_master,
insp.EMP_NAME inspector_assigned_by_emp_name, assign_insp.EMP_NAME assigned_insp_name from MDA.MW_MAINTENANCE_REQS mr
left join MDA.V_DEPARTMENT dep on dep.DEPARTMENT_ID = mr.DEPARTMENT_ID
left join MDA.CPA_VESSELS vess on vess.ID = mr.VESSEL_ID
left join PMIS.EMPLOYEE vess_mst on vess_mst.EMP_ID = vess.INCHARGE_EMP_ID
left join PMIS.EMPLOYEE insp on insp.emp_id = mr.INSPECTOR_ASSIGNED_BY_EMP_ID
left join PMIS.EMPLOYEE assign_insp on assign_insp.emp_id = mr.INSPECTOR_EMP_ID
left join MDA.MW_VESSEL_INSPECTIONS vi on vi.MAINTENANCE_REQ_ID = mr.ID
left join MDA.MW_WORKSHOPS mw on vi.WORKSHOP_ID = mw.ID
WHERE mr.STATUS IN (9, 10, 11)
AND mw.SAEN_EMP_ID = :SAEN_EMP_ID
ORDER BY mr.CREATED_AT DESC" ;
        $data = db::select($querys,['SAEN_EMP_ID' => auth()->user()->emp_id]);
        return $data;
    }

    public function getWorkshopRequisition($id)
    {
       return VesselInspection::where('maintenance_req_id',$id)
           ->where('status','!=', 'D')
           ->whereNotNull('workshop_id')
           ->with('workshop')
           ->whereHas('workshop', function ($query){
               $query->where('saen_emp_id',auth()->user()->emp_id);
           })
           ->orderBY('workshop_sl_no')
           ->get(['maintenance_req_id','workshop_id','workshop_sl_no'])
           ->unique('workshop_id');
    }

    public function getWorkshopRequisitionApproval($id)
    {
        /*return VesselInspection::where('maintenance_req_id',$id)
            ->Join('MDA.MW_INSPECTION_REQUISITIONS', 'MDA.MW_INSPECTION_REQUISITIONS.VESSEL_INSPECTION_ID', '=', 'MDA.MW_VESSEL_INSPECTIONS.id')
            ->with('workshop')
            ->groupBy('maintenance_req_id','workshop_id')
            ->get(['maintenance_req_id','workshop_id']);*/
        $role_key = json_decode(Auth::user()->roles->pluck('role_key'));
        if (in_array("MDA_SSAE_M", $role_key)){//dd(auth()->user()->emp_id);
            $querys = "SELECT DISTINCT VI.MAINTENANCE_REQ_ID, VI.WORKSHOP_ID, WR.NAME
  FROM MDA.MW_VESSEL_INSPECTIONS  VI
       LEFT JOIN MDA.MW_INSPECTION_REQUISITIONS IR
           ON IR.VESSEL_INSPECTION_ID = VI.ID
       LEFT JOIN MDA.MW_WORKSHOPS WR ON WR.ID = VI.WORKSHOP_ID
 WHERE     VI.MAINTENANCE_REQ_ID = :MAINTENANCE_REQ_ID
       AND VI.FORWARD_TO_SSAE = :FORWARD_TO_SSAE" ;
            $data = db::select($querys,['FORWARD_TO_SSAE' => auth()->user()->emp_id, 'MAINTENANCE_REQ_ID' => $id]);
            return $data;
        }else if(in_array("MDA_ASW", $role_key)){
            $querys = "SELECT DISTINCT VI.MAINTENANCE_REQ_ID, VI.WORKSHOP_ID, WR.NAME
  FROM MDA.MW_VESSEL_INSPECTIONS  VI
       LEFT JOIN MDA.MW_INSPECTION_REQUISITIONS IR
           ON IR.VESSEL_INSPECTION_ID = VI.ID
       LEFT JOIN MDA.MW_WORKSHOPS WR ON WR.ID = VI.WORKSHOP_ID
 WHERE     VI.MAINTENANCE_REQ_ID = :MAINTENANCE_REQ_ID
       AND VI.FORWARD_TO_ASW = :FORWARD_TO_ASW" ;
            $data = db::select($querys,['FORWARD_TO_ASW' => auth()->user()->emp_id, 'MAINTENANCE_REQ_ID' => $id]);
            return $data;
        }else if(in_array("MDA_XEN", $role_key)){
            $querys = "SELECT DISTINCT VI.MAINTENANCE_REQ_ID, VI.WORKSHOP_ID, WR.NAME
  FROM MDA.MW_VESSEL_INSPECTIONS  VI
       LEFT JOIN MDA.MW_INSPECTION_REQUISITIONS IR
           ON IR.VESSEL_INSPECTION_ID = VI.ID
       LEFT JOIN MDA.MW_WORKSHOPS WR ON WR.ID = VI.WORKSHOP_ID
 WHERE     VI.MAINTENANCE_REQ_ID = :MAINTENANCE_REQ_ID
       AND VI.FORWARD_TO_XEN = :FORWARD_TO_XEN" ;
            $data = db::select($querys,['FORWARD_TO_XEN' => auth()->user()->emp_id, 'MAINTENANCE_REQ_ID' => $id]);
            return $data;
        }

    }

    public function getWorkshopRequisitionItemByInspectionJob($id,$job_no=null)
    {
        return InspectionRequisitionDtl::where('vessel_inspection_id',$id)
                                        ->whereNotIn('status',['D'])
                                        ->with('product','unit')
                                        ->get();
    }

    public function getMaintenanceReqDataByWorkshop($id, $workshopId)
    {
        /*$sql = "select vi.tp_req_yn from MDA.MW_VESSEL_INSPECTIONS vi
where vi.MAINTENANCE_REQ_ID = :MAINTENANCE_REQ_ID
and vi.WORKSHOP_ID = :WORKSHOP_ID";
        $data = db::select($sql,['MAINTENANCE_REQ_ID' => $id, 'WORKSHOP_ID' => $workshopId]);dd($data);
        if($data->tp_req_yn=='Y' || $data->tp_req_yn=='C'){
            return DB::table('MDA.MW_VESSEL_INSPECTIONS')
                ->select(['MDA.MW_VESSEL_INSPECTIONS.*','MDA.MW_INSPECTION_JOBS.name','MDA.MW_INSPECTION_REQUISITIONS.REQUISITION_NUMBER','MDA.MW_MAINTENANCE_INSPECTOR.INSPECTOR_JOB_NUMBER'])
                ->where('MDA.MW_VESSEL_INSPECTIONS.maintenance_req_id', $id)
                ->where('MDA.MW_VESSEL_INSPECTIONS.workshop_id', $workshopId)
                ->leftJoin('MDA.MW_INSPECTION_REQUISITIONS','MDA.MW_INSPECTION_REQUISITIONS.VESSEL_INSPECTION_ID','=','MDA.MW_VESSEL_INSPECTIONS.id')
                ->leftJoin('MDA.MW_INSPECTION_JOBS','MDA.MW_INSPECTION_JOBS.id','=','MDA.MW_VESSEL_INSPECTIONS.INSPECTION_JOB_ID')
                ->leftJoin('MDA.MW_MAINTENANCE_INSPECTOR','MDA.MW_MAINTENANCE_INSPECTOR.MAINTENANCE_REQ_ID','=','MDA.MW_VESSEL_INSPECTIONS.MAINTENANCE_REQ_ID')
                ->get();
        }else{*/

            /*return DB::table('MDA.MW_VESSEL_INSPECTIONS')
                ->select(['MDA.MW_VESSEL_INSPECTIONS.*','MDA.MW_INSPECTION_JOBS.name','MDA.MW_INSPECTION_REQUISITIONS.REQUISITION_NUMBER','MDA.MW_MAINTENANCE_INSPECTOR.INSPECTOR_JOB_NUMBER','MDA.MW_INSPECTION_REQUISITIONS.STATUS'])
                ->where('MDA.MW_VESSEL_INSPECTIONS.maintenance_req_id', $id)
                ->where('MDA.MW_VESSEL_INSPECTIONS.workshop_id', $workshopId)
                ->leftJoin('MDA.MW_INSPECTION_REQUISITIONS','MDA.MW_INSPECTION_REQUISITIONS.VESSEL_INSPECTION_ID','=','MDA.MW_VESSEL_INSPECTIONS.id')
                ->leftJoin('MDA.MW_INSPECTION_JOBS','MDA.MW_INSPECTION_JOBS.id','=','MDA.MW_VESSEL_INSPECTIONS.INSPECTION_JOB_ID')
                ->leftJoin('MDA.MW_MAINTENANCE_INSPECTOR','MDA.MW_MAINTENANCE_INSPECTOR.MAINTENANCE_REQ_ID','=','MDA.MW_VESSEL_INSPECTIONS.MAINTENANCE_REQ_ID')
                ->get();*/
        /*$querys = "SELECT MVI.*, MIJ.NAME, MIR.STATUS,MIR.REQUISITION_NUMBER FROM MDA.MW_VESSEL_INSPECTIONS MVI
LEFT JOIN MDA.MW_INSPECTION_JOBS MIJ ON MIJ.ID = MVI.INSPECTION_JOB_ID
LEFT JOIN MDA.MW_INSPECTION_REQUISITIONS MIR ON MIR.VESSEL_INSPECTION_ID = MVI.ID
WHERE  MVI.MAINTENANCE_REQ_ID = :MAINTENANCE_REQ_ID
AND MVI.WORKSHOP_ID = :WORKSHOP_ID" ;*/
        $querys = "SELECT MVI.*,
       MIJ.NAME,
       MIR.STATUS,
       MIR.REQUISITION_NUMBER,
       EMP_ASW.EMP_NAME ASW_NAME,
       EMP_SSAE.EMP_NAME SSAE_NAME,
       EMP_XEN.EMP_NAME XEN_NAME,
       EMP_ASW.EMP_CODE ASW_CODE,
       EMP_SSAE.EMP_CODE SSAE_CODE,
       EMP_XEN.EMP_CODE XEN_CODE,
       MIJD.REMARKS show_remarks,
       MIJD.ATTACHMENT
  FROM MDA.MW_VESSEL_INSPECTIONS  MVI
       LEFT JOIN MDA.MW_INSPECTION_JOBS MIJ ON MIJ.ID = MVI.INSPECTION_JOB_ID
       LEFT JOIN MDA.MW_MAIN_INSPECTION_JOB_DTL MIJD ON MIJD.JOB_DTL_ID = MVI.JOB_DTL_ID
       LEFT JOIN MDA.MW_INSPECTION_REQUISITIONS MIR
           ON MIR.VESSEL_INSPECTION_ID = MVI.ID
       LEFT JOIN PMIS.EMPLOYEE EMP_ASW ON EMP_ASW.EMP_ID = MVI.FORWARD_TO_ASW
       LEFT JOIN PMIS.EMPLOYEE EMP_SSAE ON EMP_SSAE.EMP_ID = MVI.FORWARD_TO_SSAE
       LEFT JOIN PMIS.EMPLOYEE EMP_XEN ON EMP_XEN.EMP_ID = MVI.FORWARD_TO_XEN
 WHERE     MVI.MAINTENANCE_REQ_ID = :MAINTENANCE_REQ_ID
       AND MVI.WORKSHOP_ID = :WORKSHOP_ID" ;
        $data = db::select($querys,['MAINTENANCE_REQ_ID' => $id,'WORKSHOP_ID'=>$workshopId]);
        return $data;
        //}

    }

    public function getMaintenanceReqAuthDataByWorkshop($id, $workshopId)
    {
        $role_key = json_decode(Auth::user()->roles->pluck('role_key'));
        if (in_array("MDA_SAE_M", $role_key)) {
            return DB::table('MDA.MW_VESSEL_INSPECTIONS')
                ->select(['MDA.MW_VESSEL_INSPECTIONS.*','MDA.MW_INSPECTION_JOBS.name','MDA.MW_INSPECTION_REQUISITIONS.REQUISITION_NUMBER','MDA.MW_INSPECTION_REQUISITIONS.STATUS','EMP_ASW.EMP_NAME AS ASW_NAME','EMP_XEN.EMP_NAME AS XEN_NAME'])
                ->where('MDA.MW_VESSEL_INSPECTIONS.maintenance_req_id', $id)
                ->where('MDA.MW_VESSEL_INSPECTIONS.workshop_id', $workshopId)
                ->RightJoin('MDA.MW_INSPECTION_REQUISITIONS','MDA.MW_INSPECTION_REQUISITIONS.VESSEL_INSPECTION_ID','=','MDA.MW_VESSEL_INSPECTIONS.id')
                ->leftJoin('MDA.MW_INSPECTION_JOBS','MDA.MW_INSPECTION_JOBS.id','=','MDA.MW_VESSEL_INSPECTIONS.INSPECTION_JOB_ID')
                ->leftJoin('PMIS.EMPLOYEE EMP_ASW','EMP_ASW.EMP_ID','=','MDA.MW_VESSEL_INSPECTIONS.FORWARD_TO_ASW')
                ->leftJoin('PMIS.EMPLOYEE EMP_XEN','EMP_XEN.EMP_ID','=','MDA.MW_VESSEL_INSPECTIONS.FORWARD_TO_XEN')
                ->get();
        }else if (in_array("MDA_SSAE_M", $role_key)){
            return DB::table('MDA.MW_VESSEL_INSPECTIONS')
                ->select(['MDA.MW_VESSEL_INSPECTIONS.*','MDA.MW_INSPECTION_JOBS.name','MDA.MW_INSPECTION_REQUISITIONS.REQUISITION_NUMBER','MDA.MW_INSPECTION_REQUISITIONS.STATUS','EMP_ASW.EMP_NAME AS ASW_NAME','EMP_XEN.EMP_NAME AS XEN_NAME'])
                ->where('MDA.MW_VESSEL_INSPECTIONS.maintenance_req_id', $id)
                ->where('MDA.MW_VESSEL_INSPECTIONS.workshop_id', $workshopId)
                ->where('MDA.MW_VESSEL_INSPECTIONS.FORWARD_TO_SSAE', auth()->user()->employee->emp_id)
                ->RightJoin('MDA.MW_INSPECTION_REQUISITIONS','MDA.MW_INSPECTION_REQUISITIONS.VESSEL_INSPECTION_ID','=','MDA.MW_VESSEL_INSPECTIONS.id')
                ->leftJoin('MDA.MW_INSPECTION_JOBS','MDA.MW_INSPECTION_JOBS.id','=','MDA.MW_VESSEL_INSPECTIONS.INSPECTION_JOB_ID')
                ->leftJoin('PMIS.EMPLOYEE EMP_ASW','EMP_ASW.EMP_ID','=','MDA.MW_VESSEL_INSPECTIONS.FORWARD_TO_ASW')
                ->leftJoin('PMIS.EMPLOYEE EMP_XEN','EMP_XEN.EMP_ID','=','MDA.MW_VESSEL_INSPECTIONS.FORWARD_TO_XEN')
                ->get();
        }else if (in_array("MDA_ASW", $role_key)){
            return DB::table('MDA.MW_VESSEL_INSPECTIONS')
                ->select(['MDA.MW_VESSEL_INSPECTIONS.*','MDA.MW_INSPECTION_JOBS.name','MDA.MW_INSPECTION_REQUISITIONS.REQUISITION_NUMBER','MDA.MW_INSPECTION_REQUISITIONS.STATUS','EMP_ASW.EMP_NAME AS ASW_NAME','EMP_XEN.EMP_NAME AS XEN_NAME'])
                ->where('MDA.MW_VESSEL_INSPECTIONS.maintenance_req_id', $id)
                ->where('MDA.MW_VESSEL_INSPECTIONS.workshop_id', $workshopId)
                ->where('MDA.MW_VESSEL_INSPECTIONS.FORWARD_TO_ASW', auth()->user()->employee->emp_id)
                ->RightJoin('MDA.MW_INSPECTION_REQUISITIONS','MDA.MW_INSPECTION_REQUISITIONS.VESSEL_INSPECTION_ID','=','MDA.MW_VESSEL_INSPECTIONS.id')
                ->leftJoin('MDA.MW_INSPECTION_JOBS','MDA.MW_INSPECTION_JOBS.id','=','MDA.MW_VESSEL_INSPECTIONS.INSPECTION_JOB_ID')
                ->leftJoin('PMIS.EMPLOYEE EMP_ASW','EMP_ASW.EMP_ID','=','MDA.MW_VESSEL_INSPECTIONS.FORWARD_TO_ASW')
                ->leftJoin('PMIS.EMPLOYEE EMP_XEN','EMP_XEN.EMP_ID','=','MDA.MW_VESSEL_INSPECTIONS.FORWARD_TO_XEN')
                ->get();
        }else if (in_array("MDA_XEN", $role_key)){
            return DB::table('MDA.MW_VESSEL_INSPECTIONS')
                ->select(['MDA.MW_VESSEL_INSPECTIONS.*','MDA.MW_INSPECTION_JOBS.name','MDA.MW_INSPECTION_REQUISITIONS.REQUISITION_NUMBER','MDA.MW_INSPECTION_REQUISITIONS.STATUS','EMP_ASW.EMP_NAME AS ASW_NAME','EMP_XEN.EMP_NAME AS XEN_NAME'])
                ->where('MDA.MW_VESSEL_INSPECTIONS.maintenance_req_id', $id)
                ->where('MDA.MW_VESSEL_INSPECTIONS.workshop_id', $workshopId)
                ->where('MDA.MW_VESSEL_INSPECTIONS.FORWARD_TO_XEN', auth()->user()->employee->emp_id)
                ->RightJoin('MDA.MW_INSPECTION_REQUISITIONS','MDA.MW_INSPECTION_REQUISITIONS.VESSEL_INSPECTION_ID','=','MDA.MW_VESSEL_INSPECTIONS.id')
                ->leftJoin('MDA.MW_INSPECTION_JOBS','MDA.MW_INSPECTION_JOBS.id','=','MDA.MW_VESSEL_INSPECTIONS.INSPECTION_JOB_ID')
                ->leftJoin('PMIS.EMPLOYEE EMP_ASW','EMP_ASW.EMP_ID','=','MDA.MW_VESSEL_INSPECTIONS.FORWARD_TO_ASW')
                ->leftJoin('PMIS.EMPLOYEE EMP_XEN','EMP_XEN.EMP_ID','=','MDA.MW_VESSEL_INSPECTIONS.FORWARD_TO_XEN')
                ->get();
        }else{
            return DB::table('MDA.MW_VESSEL_INSPECTIONS')
                ->select(['MDA.MW_VESSEL_INSPECTIONS.*','MDA.MW_INSPECTION_JOBS.name','MDA.MW_INSPECTION_REQUISITIONS.REQUISITION_NUMBER','MDA.MW_INSPECTION_REQUISITIONS.STATUS','EMP_ASW.EMP_NAME AS ASW_NAME','EMP_XEN.EMP_NAME AS XEN_NAME'])
                ->where('MDA.MW_VESSEL_INSPECTIONS.maintenance_req_id', $id)
                ->where('MDA.MW_VESSEL_INSPECTIONS.workshop_id', $workshopId)
                ->RightJoin('MDA.MW_INSPECTION_REQUISITIONS','MDA.MW_INSPECTION_REQUISITIONS.VESSEL_INSPECTION_ID','=','MDA.MW_VESSEL_INSPECTIONS.id')
                ->leftJoin('MDA.MW_INSPECTION_JOBS','MDA.MW_INSPECTION_JOBS.id','=','MDA.MW_VESSEL_INSPECTIONS.INSPECTION_JOB_ID')
                ->leftJoin('PMIS.EMPLOYEE EMP_ASW','EMP_ASW.EMP_ID','=','MDA.MW_VESSEL_INSPECTIONS.FORWARD_TO_ASW')
                ->leftJoin('PMIS.EMPLOYEE EMP_XEN','EMP_XEN.EMP_ID','=','MDA.MW_VESSEL_INSPECTIONS.FORWARD_TO_XEN')
                ->get();
        }

        /*return DB::table('MDA.MW_VESSEL_INSPECTIONS')
            ->select(['MDA.MW_VESSEL_INSPECTIONS.*','MDA.MW_INSPECTION_JOBS.name','MDA.MW_INSPECTION_REQUISITIONS.REQUISITION_NUMBER','MDA.MW_INSPECTION_REQUISITIONS.STATUS','EMP_ASW.EMP_NAME AS ASW_NAME','EMP_XEN.EMP_NAME AS XEN_NAME'])
            ->where('MDA.MW_VESSEL_INSPECTIONS.maintenance_req_id', $id)
            ->where('MDA.MW_VESSEL_INSPECTIONS.workshop_id', $workshopId)
            ->RightJoin('MDA.MW_INSPECTION_REQUISITIONS','MDA.MW_INSPECTION_REQUISITIONS.VESSEL_INSPECTION_ID','=','MDA.MW_VESSEL_INSPECTIONS.id')
            ->leftJoin('MDA.MW_INSPECTION_JOBS','MDA.MW_INSPECTION_JOBS.id','=','MDA.MW_VESSEL_INSPECTIONS.INSPECTION_JOB_ID')
            ->leftJoin('PMIS.EMPLOYEE EMP_ASW','EMP_ASW.EMP_ID','=','MDA.MW_VESSEL_INSPECTIONS.FORWARD_TO_ASW')
            ->leftJoin('PMIS.EMPLOYEE EMP_XEN','EMP_XEN.EMP_ID','=','MDA.MW_VESSEL_INSPECTIONS.FORWARD_TO_XEN')
            ->get();*/
    }

    public function getVesselInspectionData($id)
    {
        return DB::table('MDA.MW_VESSEL_INSPECTIONS')
            ->where('MDA.MW_VESSEL_INSPECTIONS.id', $id)
            ->leftJoin('MDA.MW_INSPECTION_REQUISITIONS','MDA.MW_INSPECTION_REQUISITIONS.VESSEL_INSPECTION_ID','=','MDA.MW_VESSEL_INSPECTIONS.id')
            ->first();
    }

    public function searchProduct($searchTerm)
    {
        return  Product::where(function($query) use ($searchTerm){
            $query->where(DB::raw('LOWER(name)'),'like',strtolower('%'.trim($searchTerm).'%'))
                ->orWhere('code', 'like', ''.trim($searchTerm).'%' );
        })->where('active_yn', 'Y')->orderBy('name', 'ASC')->limit(10)->get();
    }

    public function workshopOrderDataTable($request = [])
    {

        if(Auth::user()->hasPermission('CAN_VIEW_WORKSHOP_ORDER_VIEW_MDA')=='true'){
            return MaintenanceReq::whereIN('status',['8','9','10'])
                ->with('vessel','department','vesselMaster','assignedInspector')
                ->orderBy("created_at", 'desc')->get();
        }elseif(Auth::user()->hasPermission('CAN_SAE_M_MDA')=='true'){
            return MaintenanceReq::whereIN('status',['8','9','10'])
                ->where('sae_mechanical_emp_id',Auth::user()->emp_id)
                ->with('vessel','department','vesselMaster','assignedInspector')
                ->orderBy("created_at", 'desc')->get();
        }
    }


}
