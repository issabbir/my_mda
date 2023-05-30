<?php

namespace App\Http\Controllers\Mea\Brta;

use App\Contracts\Mea\Brta\BrtaContract;
use App\Contracts\Mea\Vms\CommonContract;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client as GuzzleClient;
use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Stream;
use Prewk\XmlStringStreamer\Parser;

class BrtaApiController extends Controller
{
    private $brtaContact;


    public function __construct(BrtaContract $brtaContact)
    {
        $this->brtaContact    = $brtaContact;
    }



    public function matchVehicle(Request $request){
        $regNoNumberFieldOnly = $request->get('regNoNumberFieldOnly');

        try{
            $data = $this->brtaContact->matchVehicle($regNoNumberFieldOnly);

        }catch (\Exception $e){
            return response()->json(['success' => false, 'msg' => $e->getMessage(),'data' => null]);
        }
        return response()->json( ['success' => true, 'msg'=> 'Successfully fetched data' , 'data' =>  $data["data"]["registrationNumber"] ]);

    }

    public function getvehiclewithid(Request $request){
        $regNo = $request->get('regNo');

        try{
            $data = $this->brtaContact->getCpaDataFromBrta($regNo);

        }catch (\Exception $e){
            return response()->json(['success' => false, 'msg' => $e->getMessage(),'data' => null]);
        }
        return response()->json( ['success' => true, 'msg'=> 'Successfully fetched data' , 'data' =>  $data ]);

    }

}
