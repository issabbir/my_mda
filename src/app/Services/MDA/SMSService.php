<?php

namespace App\Services\MDA;

//use GuzzleHttp\Client;
use GuzzleHttp\Client;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SMSService
{
    public function sendSMS($msisdn, $msg){

        $gwUrl =  'https://api.mobireach.com.bd/SendTextMessage?Username=cns&Password=Ikram*2017&From=8801847169958&To=' . $msisdn . '&Message=' . urlencode($msg);
        $client = new Client(); //sending sms
        $requestClient = $client->get($gwUrl);
        $responseClient = $requestClient->getBody()->read(4000);
        try {
            if (!empty($responseClient)) {
                $resXml = simplexml_load_string($responseClient);
                if (isset($resXml->ServiceClass->StatusText) && strtoupper($resXml->ServiceClass->StatusText) == 'SUCCESS') {
                    $data = [
                        'status' => true,
                        'code' => '000',
                        'msg' => 'Success to send sms',
                        'message_send_id' => isset($resXml->ServiceClass->MessageId) ? strval($resXml->ServiceClass->MessageId[0]) : '',
                        'response' => $resXml
                    ];
                } else {
                    $data = [
                        'status' => false,
                        'code' => '106',
                        'msg' => 'Failed to send sms',
                        'message_send_id' => '',
                        'response' => ''
                    ];
                }
            } else {
                $data = [
                    'status' => false,
                    'code' => '107',
                    'msg' => 'Failed to send sms',
                    'message_send_id' => '',
                    'response' => ''
                ];
            }
        } catch (\Exception $e) {
            $data = [
                'status' => false,
                'code' => '108',
                'msg' => 'Failed to send sms',
                'message_send_id' => '',
                'response' => ''
            ];
        }

       return $data;
    }
}
