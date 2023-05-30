<?php

namespace App\Managers\Cms;

use App\Contracts\Cms\NotificationsContract;
use App\Entities\Cms\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class NotificationsManager implements NotificationsContract
{

    /**
     * Get one or more store based on parameter
     *
     * @param null $id
     * @return mixed
     */
    public function count()
    {
       return Notification::where('notification_type','WEB')
                            ->whereNull('web_notify_read_date')
                            ->where('web_notify_to', auth()->user()->employee->emp_id)->count();
    }
    public function allData()
    {
       return Notification::where('notification_type','WEB')
           ->where('web_notify_to', auth()->user()->employee->emp_id)
           ->orderBY('created_at','desc')
           ->get();
    }
    public function recentNotifications()
    {
       return Notification::where('notification_type','WEB')
           ->whereNull('web_notify_read_date')
           ->where('web_notify_to',auth()->user()->employee->emp_id)->limit(5)
           ->orderBY('created_at','desc')
           ->get();
    }
    public function  seenNotifications($id)
    {
       return Notification::where('notification_type','WEB')
           ->where('web_notify_to', auth()->user()->employee->emp_id)
           ->whereNotNull('web_notify_read_date')
           ->orderBY('created_at','desc')
           ->get();
    }
    public function updateNotifications()
    {
       $seenNotifications =  Notification::where('notification_type','WEB')
                                            ->whereNull('web_notify_read_date')
                                           ->where('web_notify_to', auth()->user()->employee->emp_id)->get();
          try {
              DB::beginTransaction();
              $response = [];
               foreach ($seenNotifications as $seenNotification){
                   if($seenNotification['status'] == 'P'){
                       $status_code = sprintf("%4000s", "");
                       $status_message = sprintf("%4000s", "");
                          $params = [
                            "p_action_type" =>'U',
                            "p_id" =>$seenNotification['id'],
                            "p_maintenance_req_id" => '',
                            "p_web_message_link" => '',
                            "p_web_message_link_title" =>'',
                            "p_web_notify_to" =>'',
                            "p_web_notify_read_date" =>'',
                            "p_create_by" => Auth::id(),
                            "p_status" => '',
                            "O_STATUS_CODE" => &$status_code,
                            "O_STATUS_MESSAGE" => &$status_message,
                        ];
                        DB::executeProcedure("MDA.MDA_MW_CORE_PROCE.MW_WEB_NOTIFICATIONS", $params);
                        $response = ["status" => true, "status_code" => 1, "data" => $params, "status_message" => 'Updated successfully'];
                  }else{
                       $response = ["status" => false, "status_code" => 1, "status_message" => 'Already Read'];
                   }
               }
               DB::commit();
               } catch (\Exception $e) {
//                  dd($e);
                 DB::rollBack();
               dd($e->getMessage());
                    $response = ["status" => false, "status_code" => 99, "status_message" => 'Please try again later.'];
                }
               // dd($response);
                return $response;
             }


}
