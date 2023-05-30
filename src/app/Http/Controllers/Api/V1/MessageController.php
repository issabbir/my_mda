<?php

namespace App\Http\Controllers\Api\V1;
use App\Contracts\MessageContract;
use App\Entities\Cms\FuelConsumptionMst;
use App\Http\Controllers\Controller;
use App\Helpers\HelperClass;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    protected $messageManager;

    public function __construct(MessageContract $messageManager)
    {
        $this->messageManager = $messageManager;
    }

    public function mailSendApi()
    {
         try{

        $messages = $this->messageManager->queueMessages();
        $mail = false;
        $consumption_data = '';
        foreach ($messages as $message) {
            if ($message->receiver_email == ''){
                continue;
            }
            switch (strtoupper($message->message_type)) {
                case 'CM_VESSEL_MASTER_MAIL':
                    $consumption_data = FuelConsumptionMst::where('fuel_consumption_mst_id',$message->message_reference_id)->first();
                    break;
                default:
                    break;
              }
            $data = [
                'title' => $message->message_subject,
                'subject ' => $message->message_subject,
                'body' => $message->email_message_body,
                'message_type' => $message->message_type,
//                'date' => HelperClass::defaultDateTimeFormat($consumption_data->meeting_date,'LOCALDATE'),
//                'start_time' => HelperClass::defaultDateTimeFormat($consumption_data->start_time,'TIME'),
//                'end_time' => HelperClass::defaultDateTimeFormat($consumption_data->end_time,'TIME'),
//                'location' => $consumption_data->meeting_location,
                'host_name' => $message->host_name,
                'receiver_name' => $message->receiver_name,
//                'email' => $message->receiver_email,
                'email' => 'hurokan91@gmail.com'
            ];
            $obj = new  SendMail($data, $message->message_subject);
            $mail = $this->messageManager->sendMail($obj, $message->receiver_email);
            //dd($mail);
            if ($mail) {
                $counter = 0;
                $data = ['email_send_status' => 'C', 'try_counter' => $counter];
                $this->messageManager->update($data, $message->message_id);
            } else {
                $counter = $message->try_counter + 1;
                $data = ['email_send_status' => 'R', 'try_counter' => $counter];
                $this->messageManager->update($data, $message->message_id);
            }
        }

        if ($mail) {
            $message = ['status' => true, 'message' => 'Successfully send Email'];
        } else {
            $message = ['status' => false, 'message' => 'Failed to send email, please try again.'];
        }
       }catch (\Exception $e){
            $message = ['status' => false, 'message' => $e->getMessage()];
        }
        return response($message);

    }

    public function smsSendApi()
    {
        $message = $this->messageManager->sendSms();
        return response($message);
    }

    public function mweSmsSendApi()
    {
        $message = $this->messageManager->sendMweSms();
        return response($message);
    }
}
