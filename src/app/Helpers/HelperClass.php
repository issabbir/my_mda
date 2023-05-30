<?php
namespace App\Helpers;

use App\Entities\Mda\OptionConfig;
use App\Entities\Mwe\InspectionRequisition;
use App\Entities\Mwe\InspectionRequisitionDtl;
use App\Entities\Mwe\MaintenanceReq;
use App\Entities\Mwe\StatusConfig;
use App\Managers\Authorization\AuthorizationManager;
use App\Managers\Mwe\WorkshopManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HelperClass
{
    public $id;
    public $links;

    public static function breadCrumbs($routeName)
    {
        $bmFunc = function ($routeName){
            $breadMenus = [];
            try {
                $authorizationManager = new AuthorizationManager();
                $getRouteMenuId = $authorizationManager->findSubMenuId($routeName);
                if ($getRouteMenuId && !empty($getRouteMenuId)) {
                    $breadMenus[] = $bm = $authorizationManager->findParentMenu($getRouteMenuId);
                    if ($bm && isset($bm['parent_submenu_id']) && !empty($bm['parent_submenu_id'])) {
                        $breadMenus[] = $authorizationManager->findParentMenu($bm['parent_submenu_id']);
                    }
                }
            } catch (\Exception $e) {
                return false;
            }
            return is_array($breadMenus) ? array_reverse($breadMenus) : false;

        };

        $bms = $bmFunc($routeName);
        if($bms){
            return $bms;
        }elseif(substr($routeName,-4) == 'edit'){
            $bms = $bmFunc(substr($routeName,0,-5));
            if($bms){
                $bms[] =['submenu_name' => 'Edit', 'action_name' => ''];
            }
            return $bms;

        }elseif(substr($routeName,-4) == 'view'){
            $bms = $bmFunc(substr($routeName,0,-5));
            if($bms){
                $bms[] =['submenu_name' => 'View', 'action_name' => ''];
            }
            return $bms;

        }else{
            if (in_array($routeName, ['mwe.operation.workshop-auth-requisition-create', ''])) {
                return [
                    ['submenu_name' => 'Maintenance', 'action_name' => 'mwe.operation.workshop-requisition-auth'],
                    ['submenu_name' => 'Workshop ', 'action_name' => ''],
                    ['submenu_name' => 'Requisition', 'action_name' => '']
                ];
            }elseif (in_array($routeName, ['mwe.operation.maintenance-req-auth-by-xen', ''])) {
                return [
                    ['submenu_name' => 'Maintenance', 'action_name' => 'mwe.operation.maintenance-request'],
                    ['submenu_name' => 'Request ', 'action_name' => ''],
//                    ['submenu_name' => 'Authorization', 'action_name' => '']
                ];
            }elseif (in_array($routeName, ['mwe.operation.request-inspection-report', ''])) {
                return [
                    ['submenu_name' => 'Maintenance', 'action_name' => ''],
                    ['submenu_name' => 'Vessel Inspection', 'action_name' => ''],
                ];
            } elseif (in_array($routeName, ['mwe.operation.workshop-requisition-create', ''])) {
                return [
                    ['submenu_name' => 'Workshop Requisition', 'action_name' => ''],
                ];
            }   else {
                return false;
            }
        }

        /*if (in_array($routeName, ['berthing-schedule', 'berthing-schedule-edit'])) {
            return [
                ['submenu_name' => 'Berthing Schedule', 'action_name' => 'berthing-schedule'],
                ['submenu_name' => 'Edit', 'action_name' => '']
            ];
        }   else {
            $breadMenus = [];

            try {
                $authorizationManager = new AuthorizationManager();
                $getRouteMenuId = $authorizationManager->findSubMenuId($routeName);
                if ($getRouteMenuId && !empty($getRouteMenuId)) {
                    $breadMenus[] = $bm = $authorizationManager->findParentMenu($getRouteMenuId);
                    if ($bm && isset($bm['parent_submenu_id']) && !empty($bm['parent_submenu_id'])) {
                        $breadMenus[] = $authorizationManager->findParentMenu($bm['parent_submenu_id']);
                    }
                }
            } catch (\Exception $e) {
                return false;
            }
            return is_array($breadMenus) ? array_reverse($breadMenus) : false;
        }*/

    }

    public function remove_special_char($text)
    {
        $t = $text;
        $specChars = array(
            ' ' => '-', '!' => '', '"' => '',
            '#' => '', '$' => '', '%' => '',
            '&amp;' => '', '\'' => '', '(' => '',
            ')' => '', '*' => '', '+' => '',
            ',' => '', '₹' => '', '.' => '',
            '/-' => '', ':' => '', ';' => '',
            '<' => '', '=' => '', '>' => '',
            '?' => '', '@' => '', '[' => '',
            '\\' => '', ']' => '', '^' => '',
            '_' => '', '`' => '', '{' => '',
            '|' => '', '}' => '', '~' => '',
            '-----' => '-', '----' => '-', '---' => '-',
            '/' => '', '--' => '-', '/_' => '-',
        );

        foreach ($specChars as $k => $v) {
            $t = str_replace($k, $v, $t);
        }

        return $t;
    }

    public static function defaultDateTimeFormat($datetime, $format='DATETIME')
    {
        switch (strtoupper($format)){
            case 'DATETIME':
                $data =  date("d-m-Y h:i A", strtotime($datetime));
                break;
            case 'DATE':
                $dateTime = new \DateTime($datetime);
                $data = $dateTime->format('d/m/Y');
                break;
            case 'TIME':
                $data =  date("h:i A", strtotime($datetime));
                break;
            case 'LOCALDATE':
                $dateTime = new \DateTime($datetime);
                $data = $dateTime->format('d/m/Y');
                break;
            default:
                $data =  date("d-m-Y h:i A", strtotime($datetime));
                break;
        }
        return $data;

    }

    public static function defaultDateTimeDiff($startDateTime, $endDateTime)
    {
        try {
            if (strtotime($startDateTime) >= strtotime($endDateTime)) {
                $res = false;
            } else {
                $res = true;
            }
        } catch (\Exception $e) {
            $res = false;
        }

        return $res;

    }

    /**
     * @return mixed
     */
    public static function checkIsExternalModule()
    {
        return Session::get('ref_module_key');
    }

    public static function convert_date_form_ddmmyyyy_to_oracle_date($date_form_ddmmyyyy){
        if($date_form_ddmmyyyy == "" || $date_form_ddmmyyyy == null){
            return null;
        }

        $final_date =  Carbon::createFromFormat('d/m/Y', $date_form_ddmmyyyy);
        return date('Y-m-d', strtotime($final_date));

    }

    public static function defaultDateDiff($startDateTime, $endDateTime)
    {
        try {
            if (strtotime($startDateTime) > strtotime($endDateTime)) {
                $res = false;
            } else {
                $res = true;
            }
        } catch (\Exception $e) {
            $res = false;
        }

        return $res;

    }

    public static function getStatus($status){
        switch (strtoupper($status)){
            case 'P':
                $res = 'PENDING';
                break;
            case 'A':
                $res = 'ACTIVE';
                break;
            case 'I':
                $res = 'INACTIVE';
                break;
            case 'R':
                $res = 'REJECTED';
                break;
            case 'C':
                $res = 'APPROVED';
                break;
            default:
                $res = '';
                break;
        }
        return $res;
    }

    public static function getRequisitionStatus($status){
        switch (strtoupper($status)){
            case 'P':
                $res = 'PENDING';
                break;
            case 'A':
                $res = 'APPROVED';
                break;
            case 'I':
                $res = 'REQUESTED FOR SUPPLY';
                break;
            case 'R':
                $res = 'RECHECKED';
                break;
            case 'C':
                $res = 'COMPLETED';
                break;
            default:
                $res = '';
                break;
        }
        return $res;
    }

    public static function getDayOnlyFromOracleDate($datetime){
         $dateTime = new \DateTime($datetime);
         $data = $dateTime->format('d');
         return $data;
    }

    public static function getStatusByInspectionId($id){
        return self::getRequisitionStatus(InspectionRequisition::where('vessel_inspection_id',$id)->value('status'));
    }

    public static function getCashCollectionVatPercentage(){
        $result =  OptionConfig::Where('option_name', 'CCS_VAT')->first();
        return $result->option_value;
    }

    public static function getConfigDate($key)
    {
        return OptionConfig::where('option_name',$key)->first();
    }

    public static function getReqStatus($code){
        return StatusConfig::where('status_code',$code)->first();
    }

    public static function getReqCurrentStatus($id){
        return MaintenanceReq::where('id',$id)->value('status');
    }

    public static function getWorkshopRequisitionItemByInspectionJob($id,$job_number=null){
        return InspectionRequisitionDtl::where('vessel_inspection_id',$id)
            ->with('product','unit')
            ->get();
    }


    public static function getRequestNumber($id){

        return $id.'-'.date('ymd-hmi').'-'. str_pad(rand(1,9999),4,"0",STR_PAD_LEFT);
    }

    public static function getStatusName($statusTag = '')
    {
        switch (strtoupper($statusTag)) {
            case 'A':
                $status ='Active';
                $name ='<span  class="badge badge-pill" style="background-color:dodgerblue">';
                $name .= $status;
                $name .='</span>';
                break;
            case 'I':
                $status = 'Inactive';
                $name = '<span  class="badge badge-pill" style="background-color:#545b62">';
                $name .= $status;
                $name .='</span>';
                break;
            case 'P':
                $status = 'Pending';
                $name = '<span  class="badge badge-pill" style="background-color:orange">';
                $name .= $status;
                $name .='</span>';
                break;
            case 'D':
                $status = 'Delete';
                $name ='<span  class="badge badge-pill" style="background-color:orangered">';
                $name .= $status;
                $name .='</span>';
                break;
            case 'C':
                $status = 'Complete';
                $name ='<span  class="badge badge-pill" style="background-color:darkgreen">';
                $name .= $status;
                $name .='</span>';
                break;
            case 'R':
                $status = 'Reject';
                $name ='<span  class="badge badge-pill" style="background-color:orangered">';
                $name .= $status;
                $name .='</span>';
                break;
            case 'Y':
                $status = 'Yes';
                $name ='<span  class="badge badge-pill" style="background-color:dodgerblue">';
                $name .= $status;
                $name .='</span>';
                break;
            case 'N':
                $status = 'No';
                $name ='<span  class="badge badge-pill" style="background-color:orangered">';
                $name .= $status;
                $name .='</span>';
                break;
            default:
                $status = 'Unknown';
                $name ='<span  class="badge badge-pill" style="background-color:orangered">';
                $name .= $status;
                $name .='</span>';
                break;
        }
        return $name;
    }

    public static function getFuelConsumptionStatus($statusTag = '')
    {
        switch (strtoupper($statusTag)) {
            case 'A':
                $status ='Approved';
                $name ='<span  class="badge badge-pill" style="background-color:dodgerblue">';
                $name .= $status;
                $name .='</span>';
                break;
            case 'I':
                $status = 'Initialize';
                $name = '<span  class="badge badge-pill" style="background-color:#795f3f">';
                $name .= $status;
                $name .='</span>';
                break;
            case 'P':
                $status = 'Pending';
                $name = '<span  class="badge badge-pill" style="background-color:orange">';
                $name .= $status;
                $name .='</span>';
                break;
            case 'D':
                $status = 'Delete';
                $name ='<span  class="badge badge-pill" style="background-color:orangered">';
                $name .= $status;
                $name .='</span>';
                break;
            case 'C':
                $status = 'Complete';
                $name ='<span  class="badge badge-pill" style="background-color:darkgreen">';
                $name .= $status;
                $name .='</span>';
                break;
            case 'R':
                $status = 'Reject';
                $name ='<span  class="badge badge-pill" style="background-color:orangered">';
                $name .= $status;
                $name .='</span>';
                break;
            case 'Y':
                $status = 'Yes';
                $name ='<span  class="badge badge-pill" style="background-color:dodgerblue">';
                $name .= $status;
                $name .='</span>';
                break;
            case 'N':
                $status = 'No';
                $name ='<span  class="badge badge-pill" style="background-color:orangered">';
                $name .= $status;
                $name .='</span>';
                break;
            default:
                $status = 'Unknown';
                $name ='<span  class="badge badge-pill" style="background-color:orangered">';
                $name .= $status;
                $name .='</span>';
                break;
        }
        return $name;
    }

    public static function dateFormat()
    {
        return 'DD-MM-YYYY';
    }

    public static function dateFormatForDB($date=null)
    {
        if (isset($date)) {
            return date("Y-m-d", strtotime($date));
        }else{
            return $date;
        }
    }

    public static function dateTimeFormatForDB($date=null)
    {
        if (isset($date)) {
            return date("Y-m-d H:i", strtotime($date));
        }else{
            return $date;
        }
    }

    public static function dateTimeFormat($date=null)
    {
        if (isset($date)) {
            return date("Y-m-d H:i", strtotime($date));
        }else{
            return $date;
        }
    }

}
