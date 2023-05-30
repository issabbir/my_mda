<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 4/20/20
 * Time: 04:54 AM
 */

namespace App\Http\Controllers\Mda;

use App\Entities\Admin\LGeoDivision;
use App\Entities\Mda\AreaInfo;
use App\Entities\Mda\CpaCargo;
use App\Entities\Mda\CpaPilot;
use App\Entities\Mda\CpaVessel;
use App\Entities\Mda\ForeignVessel;
use App\Entities\Mda\LCollectionSlipType;
use App\Entities\Mda\LCompanyInfo;
use App\Entities\Mda\LocalVessel;
use App\Entities\Mda\LPilotageType;
use App\Entities\Mda\SwingMooring;
use App\Entities\Security\Report;
use App\Enums\ModuleInfo;
use App\Enums\YesNoFlag;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Security\HasPermission;
use Illuminate\Support\Facades\DB;

class ReportGeneratorController extends Controller
{
    use HasPermission;

    public function index(Request $request)
    {
        $module = ModuleInfo::MDA_MODULE_ID;
//        $module = 31;

        $reportObject = new Report();

        if (auth()->user()->hasGrantAll()) {
            $reports = $reportObject->where('module_id', $module)->orderBy('report_name', 'ASC')->get();
        }
        else {
            $roles = auth()->user()->getRoles();
            $reports = array();
            foreach ($roles as $role) {
                if(count($role->reports)) {
                    $rpts = $role->reports->where('module_id', $module);
                    foreach ($rpts as $report) {
                        $reports[$report->report_id] = $report;
                    }
                }
            }
        }

        return view('mda.reportgenerator.index', compact('reports'));
    }

    public function reportParams(Request $request, $id)
    {
//        dd('mda');
        $report = Report::find($id);
        $divisions = LGeoDivision::all();
        $slipType = LCollectionSlipType::all();
        $sql = "select level, decode(level, 1,'Mooring 1-16', 17,'Mooring 17-28') as show_val
from dual connect by level <= 2";
        $swingmooring = db::select($sql);
        $months = array_reduce(range(1, 12), function ($result, $month) {
            $result[$month] = date('F', mktime(0, 0, 0, $month, 10));
            return $result;
        });
        $localV =LocalVessel::select('id as local_vessel_id','name')->where('status','=','A')->orderBy("name","ASC")->get();
        $sm =SwingMooring::select('id','name')->where('status','=','A')->orderBy("name","ASC")->get();
        $companyList = LCompanyInfo::where('active_yn', 'Y')->orderBy('company_name', 'ASC')->get();

        $reportForm = view('mda.reportgenerator.report-params', compact('report', 'divisions', 'months','swingmooring','localV','companyList','sm','slipType'))->render();

        return $reportForm;
    }

    public function foreignVesselData()
    {
        return ForeignVessel::select('id as foreign_vessel_id','name')->where('status','=','A')->orderBy("name","ASC")->get();
    }

    public function foreignVesselAgent()
    {
        $sql = "select agency_name as name, agency_id as id
from VSL.SECDBMS_L_AGENCY order by agency_name asc";
        return(db::select($sql));
    }

    public function foreignVesselsDetails($id)
    {
        return ForeignVessel::where("id","=",$id)->get();
    }

    public function jettyData()
    {
        return AreaInfo::select('id as jetty_id','name')->where("type_id", "=", "2")->orderBy("name","ASC")->get();
    }

    public function pilotageTypesData()
    {
        return LPilotageType::select('id as pilotage_id','name')->where("status", "=", "A")->orderBy("name","ASC")->get();
    }

    public function cargoData()
    {
        return CpaCargo::select('id as cargo_id', 'name')->where('status','=','A')->orderBy("name","ASC")->get();
    }

    public function cpaVesselData()
    {
        return  CpaVessel::select('id as vessel_id', 'name')->where('status','=','A')->orderBy("name","ASC")->get();
    }

    public function cpaPilotData()
    {
        return CpaPilot::select('id as pilot_id','name')->where('status','=','A')->orderBy("name","ASC")->get();
    }

    public function localVesselData()
    {
        return LocalVessel::select('id as local_vessel_id','name')->where('status','=','A')->orderBy("name","ASC")->get();
    }

    public function swingMooringData()
    {
        return SwingMooring::select('id as mooring_id','name')->where('status','=','A')->orderBy("name","ASC")->get();
    }
}
