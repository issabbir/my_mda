<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 5/31/2020
 * Time: 2:30 PM
 */
namespace App\Managers\Mea\Nid;

use App\Contracts\Mea\Nid\NidContract;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\DB;

class NidManager implements NidContract
{

   /* private function get_full_url($part_url,$regNo){
        $brta_base_url = $_ENV['BRTA_BASE_URL'];
       // $part_url = "getvehiclewithid/" ;
        $brta_user = $_ENV['BRTA_USER'];
        $brta_password = $_ENV['BRTA_PASSWORD'];

        $url = $brta_base_url.'/'.$part_url .'/'.$brta_user.'/'.$brta_password.'/'.$regNo;

        return $url;
    }

    private function xml_to_json_data($url){
        $client = new GuzzleClient([
            'headers' => []
        ]);
        $response = $client->request('GET', $url);

        $xmlObject = simplexml_load_string($response->getBody()->getContents());

        $json = json_encode($xmlObject);
        $array = json_decode($json, true);
        return $array;
    }


    public function getvehiclewithid($regNo)
    {
       // $brta_base_url = $_ENV['BRTA_BASE_URL'];
        $part_url = "getvehiclewithid" ;
       // $brta_user = $_ENV['BRTA_USER'];
      //  $brta_password = $_ENV['BRTA_PASSWORD'];

        $url = $this->get_full_url($part_url,$regNo);


        $data = $this->xml_to_json_data($url);

        return $data;
    }

    public function matchVehicle($regNoNumberFieldOnly)
    {

        $part_url = "matchVehicle" ;
        $url = $this->get_full_url($part_url,$regNoNumberFieldOnly);

        $data = $this->xml_to_json_data($url);

        return $data;
    }*/


    private function http_post($url, $header, $body)
    {
        // TODO: Implement http_post() method.
        if(!$header){
            $header = [];
        }

        if(!$body){
            $body = [];
        }

        $client = new GuzzleClient([
            'headers' => $header
        ]);
        $oResponse = $client->request('POST', $url, ['body' => json_encode( $body,
            JSON_UNESCAPED_SLASHES)]);

        $oResponseBody = json_decode( $oResponse->getBody());

        return $oResponseBody;
    }
    /*=http://210.4.76.133:5120/v1/nid
=cnsl
=1234
=""*/
    public function getNidDataFromServer($nid,$dob)
    {
        $nid_url = $_ENV['NID_URL'];
        $nid_username = $_ENV['NID_USERNAME'];
        $nid_password = $_ENV['NID_PASSWORD'];
        $nid_mode = $_ENV['NID_MODE'];

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $body = [
            'username' => $nid_username,
            'password' => $nid_password,
            'mode' => $nid_mode,
            'nid' => $nid,
            'dob' => $dob,
            'basic_info' => "Y",
            'present_address' => "Y",
            'permanent_address' => "Y",
            'detail_image' => "Y",
            'profile_image' => "Y",
        ];
        $response = $this->http_post($nid_url,$headers,$body);

        return $response;


    }
}
