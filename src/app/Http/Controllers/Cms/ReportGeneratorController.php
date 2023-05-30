<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 4/20/20
 * Time: 04:54 AM
 */

namespace App\Http\Controllers\Cms;

use App\Entities\Security\Report;
use App\Enums\ModuleInfo;
use App\Enums\YesNoFlag;
use App\Http\Controllers\Controller;
use App\Managers\Cms\CommonManager;
use Illuminate\Http\Request;
use App\Traits\Security\HasPermission;

class ReportGeneratorController extends Controller
{
    use HasPermission;
    private  $commonManager;
    public function __construct(CommonManager $commonManager)
    {
        $this->commonManager=$commonManager;
    }

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

        return view('cms.reportgenerator.index', compact('reports'));
    }

    public function reportParams(Request $request, $id)
    {
        $report = Report::find($id);
        $months = array_reduce(range(1, 12), function ($result, $month) {
            $result[$month] = date('F', mktime(0, 0, 0, $month, 10));
            return $result;
        });
        $duty_years=$this->commonManager->getCalenderYear();
        $duty_months=$this->commonManager->getCalenderMonths();
        $placement_types=$this->commonManager->getPlacementType();
        $placements=$this->commonManager->getPlacement();
        $reportForm = view('cms.reportgenerator.report-params', compact('report', 'duty_years','duty_months','placement_types','placements'))->render();

        return $reportForm;
    }

    public function getVesselData()
    {
        return $vessels=$this->commonManager->getVessel();
    }

    public function getFuelConsumptionByVessel($id){
        return $this->commonManager->getFuelConsumptionNoByVessel($id);
    }

}
