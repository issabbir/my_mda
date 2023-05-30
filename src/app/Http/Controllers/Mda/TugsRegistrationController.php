<?php


namespace App\Http\Controllers\Mda;


use App\Contracts\Mda\TugsRegistrationContract;
use App\Entities\Mda\LTugType;
use App\Entities\Mda\TugsRegistration;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\True_;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Services\DataTable;

class TugsRegistrationController extends Controller
{
    private $tugsManager;
    public function __construct(TugsRegistrationContract $tugsManager)
    {
        $this->tugsManager = $tugsManager;
    }

    public function tugCreate()
    {
       /* $datatable = $this->tugsManager->tugsDatatable();

        dd($datatable);*/

        $data = new LTugType();
        $types = LTugType::where("status", "!=", "D")->orderBy("name","ASC")->get();

        return view("mda.tug_registration", ["data"=>$data, "types"=>$types]);
    }

    public function tugStore(Request $request)
    {
        if ($request->isMethod("post")){
            $request->validate([
                "tug_name" => "required",
                "tug_type" => "required",
                "capacity" => "required"
                ]);

            $managerResponse = $this->tugsManager->tugsCud("I", $request);

            if ($managerResponse["status"]){
                $message = redirect()->back()->with("success", $managerResponse["status_message"]);
            }else{
                $message = redirect()->back()->with("error", $managerResponse["status_message"])->withInput();
            }

            return $message;
        }
    }

    public function tugEdit($id)
    {
        $types = LTugType::where("status", "!=", "D")->orderBy("name","ASC")->get();
        $data = TugsRegistration::Where("id","=", $id)->with("tug_type")->get();

        return view("mda.tug_registration", ["data"=>$data[0], "types"=>$types]);
    }

    public function tugUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")){
            $request->validate([
                "tug_name" => "required",
                "tug_type" => "required",
                "capacity" => "required"
            ]);

            $managerResponse = $this->tugsManager->tugsCud("U", $request, $id);

            if ($managerResponse["status"]){
                $message = redirect()->back()->with("success", $managerResponse["status_message"]);
            }else{
                $message = redirect()->back()->with("error", $managerResponse["status_message"])->withInput();
            }

            return $message;
        }
    }

    public function tugDestroy(Request $request, $id)
    {
        if ($id){
            $response = $this->tugsManager->tugsCud("D", $request, $id);

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

    public function tugDatatable($id)
    {
        $datatable = $this->tugsManager->tugsDatatable();

        return DataTables::of($datatable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('tug-registration-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('tug-registration-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data){
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }
}
