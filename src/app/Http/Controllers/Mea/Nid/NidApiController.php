<?php

namespace App\Http\Controllers\Mea\Nid;

use App\Contracts\Mea\Brta\BrtaContract;
use App\Contracts\Mea\Nid\NidContract;
use App\Contracts\Mea\Vms\CommonContract;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client as GuzzleClient;
use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Stream;
use Prewk\XmlStringStreamer\Parser;

class NidApiController extends Controller
{
    private $nidContact;


    public function __construct(NidContract $nidContact)
    {
        $this->nidContact    = $nidContact;
    }



    public function nid_data(Request $request){
        $nid = $request->get('nid');
        $dob = $request->get('dob');

        try{
            $data = $this->nidContact->getNidDataFromServer($nid,$dob);

        }catch (\Exception $e){
            return response()->json(['success' => false, 'msg' => $e->getMessage(),'data' => null]);
        }
        return response()->json( ['success' => true, 'msg'=> 'Successfully fetched data' , 'data' =>  $data->data]);

    }

}
