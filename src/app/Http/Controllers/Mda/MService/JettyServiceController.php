<?php

namespace App\Http\Controllers\Mda\MService;

use App\Contracts\Mda\JettyServiceContract;
use App\Entities\Mda\BerthingSchedule;
use App\Entities\Mda\VslJettyService;
use App\Entities\VSL\JettyList;
use App\Entities\Vtmis\VesselRegistration;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class JettyServiceController extends Controller
{
    protected $jettyServiceManager;

    public function __construct(JettyServiceContract $jettyServiceManager)
    {
        $this->jettyServiceManager = $jettyServiceManager;
    }

    public function jettyServiceCreate()
    {
        $vesselName = DB::select('select * from  VTMIS.VESSEL_REGISTRATION v, VSL.VSL_REGISTRATION_INFO r where V.REG_NO = R.REGISTRATION_NO and status_code = \'A\' order by  r.UPDATE_DATE desc');
        $berthingShedule = BerthingSchedule::where('status', '=','A')->orderBy('vessel_id', 'ASC')->get();
        $data = new VslJettyService();
        //dd($berthingShedule->vessel_id);
        return view('mda.mservice.jetty.jetty_service',[
            'data'=>$data,
            'berthingShedule'=>$berthingShedule,
            'vesselName'=>$vesselName,
            "jetty_types"=>JettyList::where("status", "=", "A")->orderBy("jetty_name","ASC")->get()
        ]);
    }

    public function jettyServiceStore(Request $request)
    {
//dd($request);
        $managerResponse = $this->jettyServiceManager->jettyServiceCud($request,"I");
        if ($managerResponse["status"]){
            $message = redirect()->back()->with("success", $managerResponse["status_message"]);
        }else{
            $message = redirect()->back()->with("error", $managerResponse["status_message"])->withInput();
        }

        return $message;
    }


    public function JettyServiceUpdate(Request $request,$id)
    {//dd($request);

        $managerRes = $this->jettyServiceManager->jettyServiceCud($request,'U', $id);

        if ($managerRes['status']) {
            $message = redirect("/jetty-service")->with('success', $managerRes['status_message']);
        } else {
            $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
        }
        return $message;
    }


    public function JettyServiceEdit($id)
    {
        $vesselName = DB::select('select * from  VTMIS.VESSEL_REGISTRATION v, VSL.VSL_REGISTRATION_INFO r where V.REG_NO = R.REGISTRATION_NO order by  r.UPDATE_DATE desc');
        $data = VslJettyService::leftJoin('vtmis.vessel_registration', 'vtmis.vessel_registration.id', '=','mda.vsl_jetty_service.vessel_no')
            ->select('mda.vsl_jetty_service.*', 'vtmis.vessel_registration.id', 'vtmis.vessel_registration.vessel_name', 'vtmis.vessel_registration.shipping_agent_name')
            ->findOrFail($id);

        return view('mda.mservice.jetty.jetty_service', [
            'vesselName' => $vesselName,
            'data' => $data,
            "jetty_types"=>JettyList::where("status", "=", "A")->orderBy("jetty_name","ASC")->get()
        ]);
    }

    public function JettyServiceDestroy(Request $request, $id)
    {
        if ($id){

            $managerRes = $this->jettyServiceManager->jettyServiceCud($request,'D', $id);
            $res = [
                'success'=>($managerRes['status'])?true:false,
                'message'=>$managerRes['status_message']
            ];
        }else{
            $res = [
                'success'=>false,
                'message'=>'Invalid Requested data'
            ];
        }
        return $res;
    }

    public function datatable($id)
    {
        $dataTable = $this->jettyServiceManager->jettyServiceDatatable();

        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                if($data->status !='A'){
                    $optionHtml =  '<a href="'. route('jetty-service-edit', $data->transaction_id) .'" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
//                    if($id != $data->transaction_id ){
                        $optionHtml .= ' <a class="confirm-delete text-danger" href="'.route('jetty-service-destroy', $data->transaction_id).'"><i class="bx bx-trash cursor-pointer"></i></a>';
//                    }
                }else{
                    $optionHtml = '';
                }

                return $optionHtml;
            })
            ->editColumn('arrival_at', function ($data) {
                return HelperClass::defaultDateTimeFormat($data->arival_date, 'date');
            })
            ->editColumn('depar_date', function ($data) {
                return  HelperClass::defaultDateTimeFormat($data->depar_date, 'date');
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 'P') {
                    $html = <<<HTML
<span class="badge badge-warning">Draft</span>
HTML;
                    return $html;
                } else {
                    $html = <<<HTML
<span class="badge badge-success">Approved</span>
HTML;
                    return $html;
                }
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function getVesselInfo($id)
    {
        $vesselsDetails = VesselRegistration::
            where('status_code','!=','D')
            ->where('id', '=', $id)
            ->orderBy('vessel_name','ASC')
            ->first();

        return response()->json( $vesselsDetails);
    }
}
