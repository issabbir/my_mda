<?php

namespace App\Http\Controllers\Mda;

use App\Entities\Pmis\LApprovalWorkflows;
use App\Entities\Pmis\WorkFlowProcess;
use App\Entities\Pmis\WorkFlowStep;
use App\Entities\Security\GenNotifications;
use App\Entities\Security\Role;
use App\Entities\Security\SecUserRoles;
use App\Entities\Security\UserRole;
use App\Enums\ModuleInfo;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkflowController extends Controller
{
    public function status(Request $request, $workflowId_out = null, $object_id_out = null)
    {
        if ($workflowId_out && $object_id_out) {
            $workflowId = $workflowId_out;
            $object_id = $object_id_out;
        } else {
            $workflowId = $request->get("workflowId");
            $object_id = $request->get("objectid");
        }
        $progressBarData = WorkFlowStep::where('approval_workflow_id', $workflowId)->orderby('process_step')->get();
        $current_step = [];
        $previous_step = [];
        $workflowProcess = WorkFlowProcess::with('workflowStep')
            ->where('workflow_object_id', $object_id)
            ->orderBy('workflow_process_id', 'DESC')
            ->whereHas('workflowStep', function ($query) use ($workflowId) {
                $query->where('approval_workflow_id', $workflowId);
            })->get();

        $option = [];
        if (!count($workflowProcess)) {
            $next_step = WorkFlowStep::where('approval_workflow_id', $workflowId)->orderBy('process_step', 'asc')->first();
        } else {
            if ($workflowProcess) {
                $current_step = $workflowProcess[0]->workflowStep;
                $sql = 'select e.emp_code, e.emp_name, d.designation
                       from cpa_security.sec_users u
                         inner join pmis.employee e on (e.emp_id = u.emp_id)
                         left join pmis.L_DESIGNATION d  on (d.designation_id = e.designation_id)
                         where user_id=:userId';
                $user = db::selectOne($sql, ['userId' => $workflowProcess[0]->insert_by]);
                $current_step->user = $user;
                $current_step->note = $workflowProcess[0]->note;
            }

            $next_step = WorkFlowStep::where('approval_workflow_id', $workflowId)->where('process_step', '>', $current_step->process_step)->orderBy('process_step', 'asc')->first();
            $previous_step = WorkFlowStep::where('approval_workflow_id', $workflowId)->where('process_step', '<', $current_step->process_step)->orderBy('process_step', 'asc')->get();
        }

        if (!empty($previous_step)) {
            foreach ($previous_step as $previous) {
                $option[] = [
                    'text' => "Backward " . $previous->workflow,
                    'value' => $previous->workflow_step_id,
                ];
            }
        }

        if (!empty($current_step)) {
            $option[] = [
                'text' => 'Forwarded ' . $current_step->workflow,
                'value' => $current_step->workflow_step_id,
                'disabled' => true
            ];
        }

        if (!empty($next_step)) {
            $option[] = [
                'text' => 'Forward ' . $next_step->workflow,
                'value' => $next_step->workflow_step_id,
            ];
        }


        $process = [];
        foreach ($workflowProcess as $wp) {
            $sql = 'select e.emp_code, e.emp_name, d.designation
                       from cpa_security.sec_users u
                         inner join pmis.employee e on (e.emp_id = u.emp_id)
                         left join pmis.L_DESIGNATION d  on (d.designation_id = e.designation_id)
                         where user_id=:userId';
            $user = db::selectOne($sql, ['userId' => $wp->insert_by]);
            $wp->user = $user;
            $process[] = $wp;
        }

        $msg = '';
        $ids = array_column($option, 'value');
        $value = $ids ? max($ids) : 0;
        $prev_val = $value - 1;
        foreach ($option as $data) {
            $msg .= '<option value="' . $data['value'] . '" ' . ($data['value'] == $value ? ' selected' : "") . ' ' . ($data['value'] == $prev_val ? ' disabled' : "") . '>' . $data['text'] . '</option>';
        }

        return response(
            [
                'workflowProcess' => $process,
                'progressBarData' => $progressBarData,
                'next_step' => $next_step,
                'previous_step' => $previous_step,
                'current_step' => $current_step,
                'options' => $msg,
            ]
        );
    }

    public function store(Guard $auth, Request $request)
    {

        $workflowId = $request->get("workflow_id");
        $object_id = $request->get("object_id");
        $status_id = $request->get("status_id");

        $getRole = WorkFlowStep::where('approval_workflow_id',$workflowId)->where('workflow_step_id',$status_id)->first();

        $all_workflow_data = WorkFlowStep::where('approval_workflow_id', $workflowId)
            ->get(['workflow_step_id'])
            ->pluck('workflow_step_id')
            ->toArray();
        if($workflowId=='13'){
            if (in_array($request->get("status_id")+1, $all_workflow_data)){
                $step = WorkFlowStep::where('workflow_step_id', $request->get("status_id")+1)->first();//dd($step->user_role);
                $application = DB::table('MDA.PILOTAGES')->where('ID', $object_id)->update(array('USER_ROLE' => $step->user_role));
            }
        }
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "p_workflow_process_id" => $workflowId,//$request->get("workflow_process_id"),
                "p_workflow_object_id" => $object_id,
                "p_workflow_step_id" => $request->get("status_id"),//$request->get("workflow_step_id"),
                "p_note" => $request->get("note"),
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];
            DB::executeProcedure("PMIS.workflow_Process_entry", $params);
        } catch (\Exception $e) {
            return ["exception" => true, "status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }


        $step = WorkFlowStep::where('workflow_step_id', $request->get("status_id"))->get();

        if(!empty($getRole)){
            if($getRole->workflow_key!='APPROVE'){

                $role_id = Role::where('role_key', $getRole->workflow_key)->value('role_id');
                $user_roles = SecUserRoles::where('role_id', $role_id)->get();
                foreach ($user_roles as $user_role){

                    $operator_notification = [
                        "p_notification_to" => $user_role->user_id,
                        "p_insert_by" => Auth::id(),
                        "p_note" => $request->get("note"),
                        "p_priority" => null,
                        "p_module_id" => 35,
                        "p_target_url" => $request->get("get_url")
                    ];
                    DB::executeProcedure("cpa_security.cpa_general.notify_add", $operator_notification);
                }
            }
        }
        /*if ($step[0]->role_yn == 'N') {

            $controller_user_notification = [
                "p_notification_to" => $step[0]->user_id,
                "p_insert_by" => Auth::id(),
                "p_note" => $request->get("note"),
                "p_priority" => null,
                "p_module_id" => 35,
                "p_target_url" => $request->get("get_url")
            ];
            DB::executeProcedure("cpa_security.cpa_general.notify_add", $controller_user_notification);

        } else {
            $role_id = Role::where('role_key', $step[0]->user_role)->value('role_id');
            $user_roles = UserRole::where('role_id', $role_id)->get();
            foreach ($user_roles as $user_role) {
                $controller_user_notification = [
                    "p_notification_to" => $user_role->user_id,
                    "p_insert_by" => Auth::id(),
                    "p_note" => $request->get("note"),
                    "p_priority" => null,
                    "p_module_id" => 34,
                    "p_target_url" => $request->get("get_url")
                ];
                DB::executeProcedure("cpa_security.cpa_general.notify_add", $controller_user_notification);
            }
        }*/

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $status_message);

        return redirect()->back()->with('message', $status_message);

    }

    public function load_workflow()
    {
        $msg = '';
        $module_id = ModuleInfo::MDA_MODULE_ID;
        $option = LApprovalWorkflows::where('module_id', $module_id)->orderBy('approval_workflow_id', 'asc')->get();

        foreach ($option as $data) {
            $msg .= '<option value="' . $data['approval_workflow_id'] . '">' . $data['work_flow_name'] . '</option>';
        }
        return response(
            [
                'options' => $msg,
            ]
        );
    }

    public function assign_workflow(Request $request)
    {
        $application_id = $request->get('application_id_flow');
        $workFlowId = $request->get('status_id');
        $table = $request->get('t_name');
        $column = $request->get('c_name');

        if ($application_id) {
            $application = DB::table($table)->where($column, $application_id)->update(array('workflow_process' => $workFlowId));
            if ($application == 1) {
                $status_message = 'Workflow Assigned Successfully.';
                session()->flash('m-class', 'alert-success');
                session()->flash('message', $status_message);

                return redirect()->back()->with('message', $status_message);
            } else {
                $status_message = 'Something went wrong.';
                session()->flash('m-class', 'alert-danger');
                session()->flash('message', $status_message);

                return redirect()->back()->with('message', $status_message);
            }
        }
    }

    public function get_workflow(Request $request)
    {
        $row_id = $request->get('row_id');
        $table = $request->get('t_name');
        $column = $request->get('c_name');
        $option = DB::table($table)->where($column, $row_id)->first();
        $workflow_process = $option->workflow_process;

        return $workflow_process;
    }

    function updateNotification(Request $request){
        if($request->get("notification_id")){
            $result = GenNotifications::where('notification_id', $request->get("notification_id"))->update(['seen_yn' => 'Y']);
        }
    }
}
