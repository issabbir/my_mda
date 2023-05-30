<?php

namespace App\Http\Controllers\Mwe;

use App\Contracts\Mwe\MaintenanceReqContract;
use App\Contracts\Mwe\SettingsContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Entities\Mda\CompanyVesselInfo;
use App\Entities\Mda\CpaVessel;
use App\Entities\Mda\Employee;
use App\Entities\Mda\LCompanyInfo;
use App\Entities\Mda\LLicenseOffice;
use App\Entities\Mda\LocalVessel;
use App\Entities\Mwe\Department;
use App\Entities\Mwe\EmpOfficeSetup;
use App\Entities\Mwe\InspectionJob;
use App\Entities\Mwe\MaintenanceReq;
use App\Entities\Mwe\MaintenanceSchedule;
use App\Entities\Mwe\Product;
use App\Entities\Mwe\Slipway;
use App\Entities\Mwe\Unit;
use App\Entities\Mwe\Vessel;
use App\Entities\Mwe\Works;
use App\Entities\Mwe\Workshop;
use App\Enums\Pmis\Employee\Statuses;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Pmis\Employee\EmployeeManager;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SettingsController extends Controller
{
    public $settingsManager;
    public $employeeManager;
    public $maintenanceReqManager;

    public function __construct(SettingsContract $settingsManager, EmployeeContract $employeeManager, MaintenanceReqContract $maintenanceReqManager)
    {
        $this->settingsManager = $settingsManager;
        $this->employeeManager = $employeeManager;
        $this->maintenanceReqManager = $maintenanceReqManager;
    }

    /************Start workshop setup*************/
    public function slipwayIndex(Request $request)
    {
        $data = new Slipway();
        return view('mwe.slipway', ['data' => $data]);
    }

    public function slipwayStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
            ]);
            $managerRes = $this->settingsManager->slipwayCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function slipwayEdit($id)
    {
        return view('mwe.slipway', [
            'data' => Slipway::findOrFail($id)
        ]);
    }

    public function slipwayUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
            ]);
            $managerRes = $this->settingsManager->slipwayCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function slipwayDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->slipwayCud('D', $request, $id);
            $res = [
                'success' => ($managerRes['status']) ? true : false,
                'message' => $managerRes['status_message']
            ];
        } else {
            $res = [
                'success' => false,
                'message' => 'Invalid required data.'
            ];
        }
        return $res;
    }

    public function slipwayDatatable($id)
    {
        $dataTable = $this->settingsManager->slipwayDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->editColumn('name', function ($data) {
                return !empty($data['name']) ? $data['name'] : '';
            })
            ->editColumn('capacity', function ($data) {
                return !empty($data['capacity']) ? $data['capacity'] : '';
            })
            ->addColumn('action', function ($data) use ($id) {
                $optionHtml = '<a href="' . route('mwe.setting.slipway-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if ($id != $data['id']) {
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('mwe.setting.slipway-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }

    /************Start maintenance schedule setup*************/
//
//    public function index(Request $request)
//    {
//        $data = new MaintenanceReq();
//        $departments=Department::where('status', '=','A')->orderBy('name', 'ASC')->get();
//        $vessels=Vessel::where('status', '=','A')->orderBy('name', 'ASC')->get();
//        return view('mwe.maintenance_req',
//            ['departments'=>$departments,
//                'vessels'=>$vessels,
//                'data'=>$data,
//            ]);
//    }
//
    public function maintenanceScheduleIndex(Request $request)
    {
        $data = new MaintenanceSchedule();
        $departments = Department::all()->sortBy('name');
        $vessels = CpaVessel::all()->sortBy('name');
        return view('mwe.maintenance_schedule',
            ['data' => $data,
                'departments' => $departments,
                'vessels' => $vessels,
            ]);
    }

    public function maintenanceScheduleStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'vessel_id' => 'required',
                'department_id' => 'required',
                'next_maintenance_at' => 'required',
                'doc_master_id' => 'required',
            ]);
            $managerRes = $this->settingsManager->maintenanceScheduleCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function maintenanceScheduleEdit($id)
    {
        $departments = Department::all()->sortBy('name');
        $vessels = $this->maintenanceReqManager->showVesselMaster(isset(MaintenanceSchedule::findOrFail($id)->vessel_id) ? MaintenanceSchedule::findOrFail($id)->vessel_id : '');
        $doc_master = $this->employeeManager->getEmployee(isset(MaintenanceSchedule::findOrFail($id)->doc_master_id) ? MaintenanceSchedule::findOrFail($id)->doc_master_id : '');
        return view('mwe.maintenance_schedule', [
            'data' => MaintenanceSchedule::findOrFail($id),
            'departments' => $departments,
            'vessels' => $vessels,
            'doc_master' => $doc_master,
        ]);
    }

    public function maintenanceScheduleUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'vessel_id' => 'required',
                'department_id' => 'required',
                'next_maintenance_at' => 'required',
                'status' => 'required',
                'doc_master_id' => 'required',
            ]);
            $managerRes = $this->settingsManager->maintenanceScheduleCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function maintenanceScheduleDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->maintenanceScheduleCud('D', $request, $id);
            $res = [
                'success' => ($managerRes['status']) ? true : false,
                'message' => $managerRes['status_message']
            ];
        } else {
            $res = [
                'success' => false,
                'message' => 'Invalid required data.'
            ];
        }
        return $res;
    }

    public function maintenanceScheduleDatatable($id)
    {
        $dataTable = $this->settingsManager->maintenanceScheduleDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->editColumn('vessel', function ($data) {
                return !empty($data['vessel']) ? $data['vessel']['name'] : '';
            })
            ->editColumn('department', function ($data) {
                return !empty($data['department']) ? $data['department']['name'] : '';
            })
            ->editColumn('last_maintenance_at', function ($data) {
                return (!$data['last_maintenance_at']) ? '' : HelperClass::defaultDateTimeFormat($data['last_maintenance_at'], 'date');
            })
            ->editColumn('last_undocking_at', function ($data) {
                return (!$data['last_undocking_at']) ? '' : HelperClass::defaultDateTimeFormat($data['last_undocking_at'], 'date');
            })
            ->editColumn('next_maintenance_at', function ($data) {
                return (!$data['next_maintenance_at']) ? '' : HelperClass::defaultDateTimeFormat($data['next_maintenance_at'], 'date');
            })
            ->addColumn('action', function ($data) use ($id) {
                $optionHtml = '<a href="' . route('mwe.setting.maintenance-schedule-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if ($id != $data['id']) {
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('mwe.setting.maintenance-schedule-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return isset($data['status']) && $data['status'] == 'P' ? 'Published' : 'Draft';
            })
            ->make(true);
    }

    public function getLastMaintenanceSchedule($vessel_id)
    {
        $last_maintenance_date = $this->settingsManager->getLastVesselMaintenanceDate($vessel_id);
        $maintenance_duration = HelperClass::getConfigDate('MW_MAINTENANCE_DURATION');
        $formated_maintenance_duration = $maintenance_duration->option_value . 'days';
        $next_maintenance_date = date_add(new \DateTime(date("Y-m-d", strtotime((!$last_maintenance_date) ? date('Y-m-d') : $last_maintenance_date))), date_interval_create_from_date_string($formated_maintenance_duration))->format('Y-m-d');
        return $data = [
            'last_maintenance_date' => (!$last_maintenance_date) ? '' : \date("Y-m-d", strtotime($last_maintenance_date)),
            'next_maintenance_date' => $next_maintenance_date,
        ];
    }

    /************Start workshop setup*************/
    public function workshopIndex(Request $request)
    {
        $data = new Workshop();
        return view('mwe.workshop',
            ['data' => $data
            ]);
    }

    public function workshopStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'name' => 'required',
                'location' => 'required',
                'in_charged_emp_id' => 'required',
                'saen_emp_id' => 'required',
                'description' => 'required',
                'status' => 'required'
            ]);
            $managerRes = $this->settingsManager->workshopCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function workshopEdit($id)
    {

        $data = Workshop::findOrFail($id);
        return view('mwe.workshop', [
            'data' => Workshop::findOrFail($id),
            'inChargeEMP' => (!$data->in_charged_emp_id) ? '' : $this->employeeManager->getEmployee($data->in_charged_emp_id),
            'saen_employee' => (!$data->saen_emp_id) ? '' : $this->employeeManager->getEmployee($data->saen_emp_id),
        ]);
    }

    public function workshopUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'name' => 'required',
                'location' => 'required',
                'in_charged_emp_id' => 'required',
                'saen_emp_id' => 'required',
                'description' => 'required',
                'status' => 'required'
            ]);
            $managerRes = $this->settingsManager->workshopCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function workshopDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->workshopCud('D', $request, $id);
            $res = [
                'success' => ($managerRes['status']) ? true : false,
                'message' => $managerRes['status_message']
            ];
        } else {
            $res = [
                'success' => false,
                'message' => 'Invalid required data.'
            ];
        }
        return $res;
    }

    public function workshopDatatable($id)
    {
        $dataTable = $this->settingsManager->workshopDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->editColumn('name', function ($data) {
                return !empty($data['name']) ? $data['name'] : '';
            })
            ->editColumn('description', function ($data) {
                return !empty($data['description']) ? $data['description'] : '';
            })
            ->editColumn('location', function ($data) {
                return !empty($data['location']) ? $data['location'] : '';
            })
            ->editColumn('in_charged', function ($data) {
                return !empty($data['authorization']) ? $data['authorization']['emp_name'] : '';
            })
            ->editColumn('saen_emp_id', function ($data) {
                return !empty($data['workshop_saen']) ? $data['workshop_saen']['emp_name'] : '';
            })
            ->addColumn('action', function ($data) use ($id) {
                $optionHtml = '<a href="' . route('mwe.setting.workshop-setting-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if ($id != $data['id']) {
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('mwe.setting.workshop-setting-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }


    /************Start Unit setup*************/
    public function unitIndex(Request $request)
    {
        $data = new Unit();
        return view('mwe.unit',
            ['data' => $data
            ]);
    }

    public function unitStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'status' => 'required'
            ]);
            $managerRes = $this->settingsManager->unitCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function unitEdit($id)
    {
        return view('mwe.unit', [
            'data' => Unit::findOrFail($id)
        ]);
    }

    public function unitUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'status' => 'required'
            ]);
            $managerRes = $this->settingsManager->unitCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function unitDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->unitCud('D', $request, $id);
            $res = [
                'success' => ($managerRes['status']) ? true : false,
                'message' => $managerRes['status_message']
            ];
        } else {
            $res = [
                'success' => false,
                'message' => 'Invalid required data.'
            ];
        }
        return $res;
    }

    public function unitDatatable($id)
    {
        $dataTable = $this->settingsManager->unitDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->editColumn('name', function ($data) {
                return !empty($data['name']) ? $data['name'] : '';
            })
            ->editColumn('description', function ($data) {
                return !empty($data['description']) ? $data['description'] : '';
            })
            ->addColumn('action', function ($data) use ($id) {
                $optionHtml = '<a href="' . route('mwe.setting.unit-setting-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if ($id != $data['id']) {
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('mwe.setting.unit-setting-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }

    /************Start Inspection Job setup*************/
    public function inspectionJobIndex(Request $request)
    {
        $data = new InspectionJob();
        return view('mwe.inspection_job',
            ['data' => $data
            ]);
    }

    public function inspectionJobStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'status' => 'required'
            ]);
            $managerRes = $this->settingsManager->inspectionJobCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function inspectionJobEdit($id)
    {
        return view('mwe.inspection_job', [
            'data' => InspectionJob::findOrFail($id)
        ]);
    }

    public function inspectionJobUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'status' => 'required'
            ]);
            $managerRes = $this->settingsManager->inspectionJobCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function inspectionJobDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->inspectionJobCud('D', $request, $id);
            $res = [
                'success' => ($managerRes['status']) ? true : false,
                'message' => $managerRes['status_message']
            ];
        } else {
            $res = [
                'success' => false,
                'message' => 'Invalid required data.'
            ];
        }
        return $res;
    }

    public function inspectionJobDatatable($id)
    {
        $dataTable = $this->settingsManager->inspectionJobDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->editColumn('name', function ($data) {
                return !empty($data['name']) ? $data['name'] : '';
            })
            ->editColumn('description', function ($data) {
                return !empty($data['description']) ? $data['description'] : '';
            })
            ->addColumn('action', function ($data) use ($id) {
                $optionHtml = '<a href="' . route('mwe.setting.inspection-job-setting-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if ($id != $data['id']) {
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('mwe.setting.inspection-job-setting-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }

    /************Product setup*************/
    public function productIndex()
    {
        $data = new Product();
        return view('mwe.product',
            ['data' => $data
            ]);
    }

    public function productDatatable($id)
    {
        $dataTable = $this->settingsManager->productDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->editColumn('name', function ($data) {
                return !empty($data['name']) ? $data['name'] : '-';
            })
            ->editColumn('description', function ($data) {
                return !empty($data['description']) ? $data['description'] : '-';
            })
            ->addColumn('action', function ($data) use ($id) {
                $optionHtml = '<a href="' . route('mwe.setting.product-setting-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                /*if($id != $data['id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('mwe.setting.product-setting-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }*/
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return isset($data['active_yn']) && $data['active_yn'] == 'Y' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }

    public function productStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'status' => 'required'
            ]);
            $managerRes = $this->settingsManager->productCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function productEdit($id)
    {
        return view('mwe.product', [
            'data' => Product::findOrFail($id)
        ]);
    }

    public function productUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'status' => 'required'
            ]);
            $managerRes = $this->settingsManager->productCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function productDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->productCud('D', $request, $id);
            $res = [
                'success' => ($managerRes['status']) ? true : false,
                'message' => $managerRes['status_message']
            ];
        } else {
            $res = [
                'success' => false,
                'message' => 'Invalid required data.'
            ];
        }
        return $res;
    }

    /************Office setup*************/
    public function officeIndex()
    {
        $data = new EmpOfficeSetup();
        $offices = LLicenseOffice::where("active_yn", "=", "Y")->orderBy("office_name","ASC")->get();
        return view('mwe.emp_ofc_setup',
            ['data' => $data, 'offices' => $offices
            ]);
    }

    public function officeDatatable($id)
    {
        $dataTable = EmpOfficeSetup::all();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('emp_name', function ($data) {
                $data['emp_code']!=null ? $abc = $data['emp_name'].' ('.$data['emp_code'].')': $abc = $data['emp_name'];
                return $abc;
            })
            ->addColumn('action', function ($data) use ($id) {
                $optionHtml = '<a href="' . route('mwe.setting.office-setup-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                return $optionHtml;
            })
            ->make(true);
    }

    public function officeStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'office_id' => 'required',
                'emp_id' => 'required',
            ]);
            $managerRes = $this->settingsManager->empOfcCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function officeEdit($id)
    {
        $offices = LLicenseOffice::where("active_yn", "=", "Y")->orderBy("office_name","ASC")->get();
        return view('mwe.emp_ofc_setup', [
            'data' => EmpOfficeSetup::findOrFail($id), 'offices' => $offices
        ]);
    }

    public function officeUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'office_id' => 'required',
                'emp_id' => 'required',
            ]);
            $managerRes = $this->settingsManager->empOfcCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function officeDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->productCud('D', $request, $id);
            $res = [
                'success' => ($managerRes['status']) ? true : false,
                'message' => $managerRes['status_message']
            ];
        } else {
            $res = [
                'success' => false,
                'message' => 'Invalid required data.'
            ];
        }
        return $res;
    }

    public function getAllEmployee(Request $request)
    {
        $searchTerm = $request->get('term');
        return Employee::where(
            [
                ['emp_status_id', '=', Statuses::ON_ROLE]
            ]
        )->where(function($query) use ($searchTerm){
            $query->where(DB::raw('LOWER(employee.emp_name)'),'like',strtolower('%'.trim($searchTerm).'%'))
                ->orWhere('employee.emp_code', 'like', ''.trim($searchTerm).'%' );
        })->orderBy('emp_code', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name']);
    }

    /************Work setup*************/
    public function workIndex()
    {
        $data = new Works();
        return view('mwe.works',
            ['data' => $data
            ]);
    }

    public function workDatatable($id)
    {
        $dataTable = $this->settingsManager->workDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('work_title', function ($data) {
                if (!empty($data->work_title)) {
                    $pieces = explode(" ", $data->work_title);
                    $first_line = implode(" ", array_splice($pieces, 0, 12));
                    $show_line = $first_line . '....';
                } else {
                    $show_line = '';
                }
                return $show_line;
            })
            ->addColumn('action', function ($data) {
                $optionHtml = '<a href="' . route('mwe.setting.work-setup-edit', $data['work_id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('mwe.setting.work-setup-destroy', $data['work_id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return isset($data['active_yn']) && $data['active_yn'] == 'Y' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }

    public function workStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required'
            ]);
            $managerRes = $this->settingsManager->workCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function workEdit($id)
    {
        return view('mwe.works', [
            'data' => Works::findOrFail($id)
        ]);
    }

    public function workUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required'
            ]);
            $managerRes = $this->settingsManager->workCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function workDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->workCud('D', $request, $id);
            $res = [
                'success' => ($managerRes['status']) ? true : false,
                'message' => $managerRes['status_message']
            ];
        } else {
            $res = [
                'success' => false,
                'message' => 'Invalid required data.'
            ];
        }
        return $res;
    }


    /************Company setup*************/
    public function companyIndex()
    {
        $data = new LCompanyInfo();
        return view('mwe.company',
            ['data' => $data
            ]);
    }

    public function companyDatatable()
    {
        $dataTable = $this->settingsManager->companyDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $optionHtml = '<a href="' . route('mwe.setting.company-setup-edit', $data['comp_id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                //$optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('mwe.setting.company-setup-destroy', $data['comp_id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                if ($data['active_yn'] == 'Y') {
                    $html = <<<HTML
<span class="badge badge-success">Active</span>
HTML;
                    return $html;
                } else {
                    $html = <<<HTML
<span class="badge badge-warning">Inactive</span>
HTML;
                    return $html;
                }
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function companyStore(Request $request)
    {//dd($request);
        $response = $this->ins_upd($request, '');
        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);
        return redirect()->route('company-setup');
    }

    public function companyUpdate(Request $request, $id)
    {
        $response = $this->ins_upd($request, $id);
        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);
        return redirect()->route('company-setup');
    }

    private function ins_upd(Request $request, $id)
    {
        $postData = $request->post();
        if ($id != null) {
            $typ = 'U';
        } else {
            $typ = 'I';
        }

        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                'P_ACTION_TYPE' => $typ,
                'P_COMP_ID' => $id,
                'P_COMPANY_NAME' => $postData['company_name'],
                'P_COMPANY_ADDRESS' => $postData['company_address'],
                'P_ACTIVE_YN' => $postData['status'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('MDA.MDA_MW_CORE_PROCE.MW_COMPANY_CUD', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }

    public function companyEdit($id)
    {
        return view('mwe.company', [
            'data' => LCompanyInfo::findOrFail($id)
        ]);
    }

    public function companyDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->workCud('D', $request, $id);
            $res = [
                'success' => ($managerRes['status']) ? true : false,
                'message' => $managerRes['status_message']
            ];
        } else {
            $res = [
                'success' => false,
                'message' => 'Invalid required data.'
            ];
        }
        return $res;
    }

    /************Company Vessel setup*************/
    public function companyVesselIndex()
    {
        $data = new CompanyVesselInfo();
        return view('mwe.company_vessel',
            ['data' => $data,
                'companyList' => LCompanyInfo::all(),
            ]);
    }

    public function searchVessel(Request $request)
    {
        $searchTerm = $request->get('search_param');
        return LocalVessel::where(
            [
                ['status', '=', 'A']
            ]
        )->where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(name)'), 'like', strtolower('%' . trim($searchTerm) . '%'));
        })->orderBy('name', 'ASC')->limit(10)->get(['id', 'name', 'owner_name', 'owner_address']);
    }

    public function getVesselInfo(Request $request)
    {
        return LocalVessel::where('id', $request->get('vessel_id'))->first();
    }

    public function searchEmployee($searchTerm)
    {
        return $this->employee->where(
            [
                ['emp_status_id', '=', Statuses::ON_ROLE]
            ]
        )->where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(employee.emp_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
                ->orWhere('employee.emp_code', 'like', '' . trim($searchTerm) . '%');
        })->orderBy('emp_code', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name']);
    }

    public function companyVesselDatatable()
    {
        $dataTable = $this->settingsManager->companyVesselDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $optionHtml = '<a href="' . route('mwe.setting.company-vessel-setup-edit', $data['cv_id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('mwe.setting.company-vessel-setup-destroy', $data['cv_id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                if ($data['active_yn'] == 'Y') {
                    $html = <<<HTML
<span class="badge badge-success">Active</span>
HTML;
                    return $html;
                } else {
                    $html = <<<HTML
<span class="badge badge-warning">Inactive</span>
HTML;
                    return $html;
                }
            })
            ->addColumn('v_assign_from', function ($query) {
                if ($query->v_assign_from == null) {
                    return '--';
                }
                return date('H:i', strtotime($query->v_assign_from));
            })
            ->addColumn('v_assign_to', function ($query) {
                if ($query->v_assign_to == null) {
                    return '--';
                }
                return date('H:i', strtotime($query->v_assign_to));
            })
            ->addColumn('v_assign_date', function ($data) {
                if ($data->v_assign_date == null) {
                    return '--';
                }
                return date('d-m-Y', strtotime($data->v_assign_date));
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function companyVesselStore(Request $request)
    {//dd($request);
        $response = $this->insrt_upd($request, '');
        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);
        return redirect()->route('company-vessel-setup');
    }

    public function companyVesselUpdate(Request $request, $id)
    {
        $response = $this->insrt_upd($request, $id);
        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);
        return redirect()->route('company-vessel-setup');
    }

    private function insrt_upd(Request $request, $id)
    {   //dd($request->all());
        $postData = $request->post();
        if ($id != null) {
            $typ = 'U';
        } else {
            $typ = 'I';
        }

        $comp = LCompanyInfo::where('comp_id', '=', $postData['comp_id'])->first();
        if($comp){
            $comp = $comp->company_name;
        }else{
            $comp = '';
        }
        $vessel = LocalVessel::where('id', '=', $postData['vessel_id'])->first();
        if($vessel){
            $vessel = $vessel->name;
        }else{
            $vessel = '';
        }

        $v_assign_date = isset($postData['v_assign_date']) ? date('Y-m-d', strtotime($postData['v_assign_date'])) : '';

        $startTime = isset($postData['schedule_from_time']) ? date('H:i:s', strtotime(str_replace(' ', '', $postData['schedule_from_time']))) : '';
        $endTime = isset($postData['schedule_to_time']) ? date('H:i:s', strtotime(str_replace(' ', '', $postData['schedule_to_time']))) : '';

        $pStartTime = $v_assign_date . ' ' . $startTime;
        $pEndTime = $v_assign_date . ' ' . $endTime;

        if($postData['schedule_from_time'] == ''){
            $pStartTime = null;
        }

        if($postData['schedule_to_time'] == ''){
            $pEndTime = null;
        }

        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                'P_ACTION_TYPE' => $typ,
                'P_CV_ID' => $id,
                'P_VESSEL_ID' => isset($postData['vessel_id'])?$postData['vessel_id']:'',
                'P_VESSEL_NAME' => $vessel,
                'P_COMP_ID' => isset($postData['comp_id'])?$postData['comp_id']:'',
                'P_COMP_NAME' => $comp,
                'P_OWNER_NAME' => $postData['owner_name'],
                'P_OWNER_ADDRESS' => $postData['owner_address'],
                'P_ASSIGN_DATE' => $v_assign_date,
                'P_ASSIGN_FROM' => $pStartTime,
                'P_ASSIGN_TO' => $pEndTime,
                'P_ACTIVE_YN' => $postData['status'],
                'P_REMARKS' => $postData['remarks'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];//dd($params);
            DB::executeProcedure('MDA.MDA_MW_CORE_PROCE.MW_COMPANY_VESSEL_CUD', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }

    public function companyVesselEdit($id)
    {
        return view('mwe.company_vessel', [
            'data' => CompanyVesselInfo::where('cv_id',$id)->first(),
            'companyList' => LCompanyInfo::all(),
        ]);
    }

    public function companyVesselDestroy(Request $request, $id)
    {
        if ($id) {
            DB::beginTransaction();
            $deleted_data=CompanyVesselInfo::where('cv_id', '=', $id)->delete();
            if($deleted_data==1){
                $res = [
                    'success' => true,
                    'message' => 'DELETED SUCCESSFULLY.'
                ];
            }else{
                $res = [
                    'success' => false,
                    'message' => 'Invalid required data.'
                ];
            }
            DB::commit();

        } else {
            $res = [
                'success' => false,
                'message' => 'Invalid required data.'
            ];
        }
        return $res;
    }
}
