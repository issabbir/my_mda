<?php


namespace App\Http\Controllers\Mea\Vms;

use App\Contracts\Mea\Vms\CommonContract;
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
    private $commonVmsManager;

    public function __construct(CommonContract $commonVmsManager)
    {
        $this->commonVmsManager     = $commonVmsManager;
    }
    public function index(Request $request)
    {
        $module = ModuleInfo::VMS_MODULE_ID;

        $reportObject = new Report();

        if(auth()->user()->hasGrantAll()) {
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

        return view('mea.vms.reportgenerator.index', compact('reports'));
    }

    public function reportParams(Request $request, $id)
    {
        $report = Report::find($id);
        $get_vehicle_reg_no_list = $this->commonVmsManager->commonDropDownLookupsList('MEA.vm_VEHICLE_pkg.get');
        $get_driver_reg_list     = $this->commonVmsManager->commonDropDownLookupsList('MEA.VM_DRIVER_PKG.get_driver_list');
        $get_maintain_master_list = $this->commonVmsManager->commonDropDownLookupsList('MEA.VM_MAINTANANCE_PKG.GET_MAINTAIN_MASTER');
        $get_workshop_list       = $this->commonVmsManager->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_workshop');
        $get_workshop_type       = $this->commonVmsManager->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_workshop_type');
        $get_driver_type         = $this->commonVmsManager->commonDropDownLookupsList('MEA.vm_lookup_pkg.get_driver_type');

        $reportForm = view('mea.vms.reportgenerator.report-params', compact('report',['get_vehicle_reg_no_list','get_driver_reg_list','get_workshop_list','get_workshop_type','get_driver_type','get_maintain_master_list']))->render();
        return $reportForm;
    }

}
