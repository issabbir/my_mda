<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 5/31/2020
 * Time: 2:30 PM
 */
namespace App\Managers\Mea\Brta;

use App\Contracts\Mea\Brta\BrtaContract;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\DB;

class BrtaManager implements BrtaContract
{

    /*API URL are:
vehicle data with id:
http://192.168.78.11:8080/brtaapi/getvehiclewithid/{user}/{pass}/{regNo}
ex: http://192.168.78.11:8080/brtaapi/getvehiclewithid/firsttrack/cns123/CHATTA%20METRO-GA-3333

Match Vehicle :
http://192.168.78.11:8080/brtaapi/matchVehicle/{user}/{pass}/{match String}
ex: http://192.168.78.11:8080/brtaapi/matchVehicle/firsttrack/cns123/3333

Get Vehicle Part :
http://192.168.78.11:8080/brtaapi/getVehiclePart/{user}/{pass}/{match String}
ex: http://192.168.78.11:8080/brtaapi/getVehiclePart/firsttrack/cns123/3333

Select Vehicle :
http://192.168.78.11:8080/brtaapi/selectVehicle/{user}/{pass}/{match String}
ex: http://192.168.78.11:8080/brtaapi/selectVehicle/firsttrack/cns123/DHAKA/1212*/

    private function get_full_url($part_url,$regNo){
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
    }

    public function getCpaDataFromBrta($regNo)
    {
        $part_url = "cpa_vehicle_detail" ;
        $url = $this->get_full_url($part_url,$regNo);
        $url = 'http://localhost:8888/cpa_vehicle_detail/firsttrack/cns123/CHATTA%20METRO-GA-3333';

        $response = $this->http_get($url,null,null);

        return $response;

    }


    public function http_get($url, $header, $body)
    {
        // TODO: Implement http_get() method.
        if(!$header){
            $header = [];
        }

        $client = new GuzzleClient([
            'headers' => $header
        ]);
        $oResponse = $client->request('GET', $url);

        $oResponseBody = json_decode( $oResponse->getBody());

        return $oResponseBody;

    }
}
