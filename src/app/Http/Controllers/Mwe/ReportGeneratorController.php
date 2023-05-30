<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 4/20/20
 * Time: 04:54 AM
 */

namespace App\Http\Controllers\Mwe;

use App\Entities\Admin\LGeoDivision;
use App\Entities\Mda\AgencyInfo;
use App\Entities\Mda\AreaInfo;
use App\Entities\Mda\CpaCargo;
use App\Entities\Mda\CpaPilot;
use App\Entities\Mda\CpaVessel;
use App\Entities\Mda\ForeignVessel;
use App\Entities\Mda\LCompanyInfo;
use App\Entities\Mda\LocalVessel;
use App\Entities\Mda\LPilotageType;
use App\Entities\Mda\SwingMooring;
use App\Entities\Mwe\Cpa_Vessels;
use App\Entities\Mwe\MaintenanceReq;
use App\Entities\Mwe\StatusConfig;
use App\Entities\Mwe\Vessel;
use App\Entities\Mwe\Workshop;
use App\Entities\Security\Report;
use App\Enums\ModuleInfo;
use App\Enums\YesNoFlag;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Security\HasPermission;

class ReportGeneratorController extends Controller
{
    use HasPermission;

    public function index(Request $request)
    {
        $module = ModuleInfo::MWE_MODULE_ID;
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

        return view('mwe.reportgenerator.index', compact('reports'));
    }

    public function reportParams(Request $request, $id)
    {
        $report = Report::find($id);
        $divisions = LGeoDivision::all();
        $workshops=Workshop::all();
        $agency_info = AgencyInfo::all();
        $vessels = Cpa_Vessels::all()->sortBy('name');
        $vessel_status = StatusConfig::all()->sortBy('status_code');
        $req_no = MaintenanceReq::where('status', '12')->orderBy('created_at', 'DESC')->get();
        $companyList = LCompanyInfo::where('active_yn', 'Y')->orderBy('company_name', 'ASC')->get();
        $localV =LocalVessel::select('id as local_vessel_id','name')->where('status','=','A')->orderBy("name","ASC")->get();

        $months = array_reduce(range(1, 12), function ($result, $month) {
            $result[$month] = date('F', mktime(0, 0, 0, $month, 10));
            return $result;
        });

        $reportForm = view('mwe.reportgenerator.report-params', compact('report', 'divisions', 'months','workshops','vessels','vessel_status','req_no','localV','companyList','agency_info'))->render();

        return $reportForm;
    }

}
