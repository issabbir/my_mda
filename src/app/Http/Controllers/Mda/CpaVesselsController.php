<?php


namespace App\Http\Controllers\Mda;


use App\Contracts\Mda\CpaVesselsContract;
use App\Entities\Mda\CpaVessel;
use App\Entities\Mda\LCpaVesselType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Services\DataTable;

class CpaVesselsController extends Controller
{
    private $cpaVesselsManager;
    public function __construct(CpaVesselsContract $cpaVesselsManager)
    {
        $this->cpaVesselsManager = $cpaVesselsManager;
    }

    public function cpaVesselCreate()
    {
        $data = new CpaVessel();
        $types = LCpaVesselType::where("status", "!=", "D")->orderBy("name","ASC")->get();
        return view("mda.cpa_vessel", ["data"=>$data, "types"=>$types]);
    }

    public function cpaVesselStore(Request $request)
    {
        if ($request->isMethod("post")){
            $request->validate([
                "cpaVessels_name" => "required",
                "cpaVessels_type" => "required"
            ]);
            $managerResponse = $this->cpaVesselsManager->cpaVesselsCud("I", $request);

            if ($managerResponse["status_code"] != 99){
                $message = redirect()->back()->with("success", $managerResponse["status_message"]);
            }else{
                $message = redirect()->back()->with("error", $managerResponse["status_message"])->withInput();
            }

            return $message;
        }
    }

    public function cpaVesselEdit($id)
    {
        $data = CpaVessel::Where("id","=", $id)->with("vessel_type")->get();
        $types = LCpaVesselType::where("status", "!=", "D")->orderBy("name","ASC")->get();
        return view("mda.cpa_vessel", ["data"=>$data[0], "types"=>$types]);
    }

    public function cpaVesselUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")){
            $request->validate([
                "cpaVessels_name" => "required",
                "cpaVessels_type" => "required"
            ]);

            $managerResponse = $this->cpaVesselsManager->cpaVesselsCud("U", $request, $id);

            if ($managerResponse["status"]){
                $message = redirect()->back()->with("success", $managerResponse["status_message"]);
            }else{
                $message = redirect()->back()->with("error", $managerResponse["status_message"])->withInput();
            }

            return $message;
        }
    }

    public function cpaVesselDestroy(Request $request, $id)
    {
        if ($id){
            $response = $this->cpaVesselsManager->cpaVesselsCud("D", $request, $id);

            $res = [
                "success" => ($response["status"]) ? True: false,
                "message" => $response["status_message"]
            ];
        }else{
            $res = [
                "success" => false,
                "message" => "Invalid require data"
            ];
        }

        return $res;
    }

    public function cpaVesselDatatable($id)
    {
        $datatable = $this->cpaVesselsManager->cpaVesselsDatatable();

        return DataTables::of($datatable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('cpa-vessel-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('cpa-vessel-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data){
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }
}
