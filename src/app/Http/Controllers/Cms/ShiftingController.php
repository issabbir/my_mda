<?php

namespace App\Http\Controllers\Cms;
use App\Contracts\Cms\CommonContract;
use App\Contracts\Cms\ShiftingContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Entities\Cms\EmployeeDuties;
use App\Entities\Cms\EmployeeDutyShifting;
use App\Entities\Cms\EmployeeOffDay;
use App\Entities\Cms\LPlacement;
use App\Entities\Cms\Shifting;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Pmis\Employee\EmployeeManager;
use Carbon\Traits\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShiftingController extends Controller
{
    public $shiftingManager;
    public $employeeManager;
    public $commonManager;

    public function __construct(ShiftingContract $shiftingManager,EmployeeContract $employeeManager,CommonContract $commonManager)
    {
        $this->shiftingManager = $shiftingManager;
        $this->employeeManager = $employeeManager;
        $this->commonManager = $commonManager;
    }

    /************Start duties *************/
    public function dutiesIndex(Request $request)
    {
        $data = new EmployeeDuties();
        $year=$this->commonManager->getCalenderYear();
        $month=$this->commonManager->getCalenderMonths();
        return view('cms.shifting.employee_duties',
            ['data'=>$data,
              'placements'=>$this->commonManager->getPlacement(),
              'vessels'=>$this->commonManager->getPlacementVessel(),
              'placement_type'=>$this->commonManager->getPlacementType(),
              'year'=>$year,
              'month'=>$month
            ]);
    }

    public function dutiesCreate(Request $request)
    {
        $data = new EmployeeDuties();
        $year=$this->commonManager->getCalenderYear();
        $month=$this->commonManager->getCalenderMonths();
        return view('cms.shifting.employee_duties_create',
            ['data'=>$data,
                'placements'=>$this->commonManager->getPlacement(),
                'vessels'=>$this->commonManager->getPlacementVessel(),
                'placement_type'=>$this->commonManager->getPlacementType(),
                'year'=>$year,
                'month'=>$month
            ]);
    }

    public function dutiesStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'employee_id' => 'required',
                'duty_year' => 'required',
                'duty_month' => 'required',
            ]);
            $managerRes = $this->shiftingManager->dutiesStore('I', $request);
            return ['data'=>$managerRes];
        }
    }

    public function searchDutySchedule(Request $request){

        $view = view('cms.shifting.partial.duties_schedule_items', [
            'schedule_items' => $this->shiftingManager->searchEmployeeDutySchedule($request),
        ])->render();
        return response()->json(['html' => $view]);
    }

    public function dutiesEdit($id)
    {
        $data=EmployeeDuties::findOrFail($id);
        $off_days=$this->shiftingManager->getOffDayById($id);
        $placement_data=$this->commonManager->getPlacement();
        $placement_type=$this->commonManager->getPlacementType();
        $vessels=$this->commonManager->getPlacementVessel();
        $duties_employee=$this->employeeManager->getEmployee(isset(EmployeeDuties::findOrFail($id)->employee_id)?EmployeeDuties::findOrFail($id)->employee_id:'');
        $year=$this->commonManager->getCalenderYear();
        $month=$this->commonManager->getCalenderMonths();
        $all_month_days=$this->commonManager->getOffDayList($data->duty_year,$data->duty_month);
        return view('cms.shifting.employee_duties_edit', [
            'data' => $data,
            'placement_type'=>$placement_type,
            'placements'=>$placement_data,
            'duties_employee'=>$duties_employee,
            'all_month_days'=>$all_month_days,
            'off_days'=>$off_days,
            'year'=>$year,
            'month'=>$month,
            'vessels'=>$vessels
        ]);

//        $view = view('cms.shifting.partial.employee_duties_edit', [
//            'data' => $data,
//            'placement_type'=>$placement_type,
//            'placements'=>$placement_data,
//            'duties_employee'=>$duties_employee,
//            'all_month_days'=>$all_month_days,
//            'off_days'=>$off_days,
//            'year'=>$year,
//            'month'=>$month,
//            'vessels'=>$vessels
//        ])->render();
//        return response()->json(['html' => $view]);
    }

    public function dutiesUpdate(Request $request, $id)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'employee_id' => 'required',
                'status' => 'required',
            ]);
            $managerRes = $this->shiftingManager->dutiesStore('U', $request, $id);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function dutiesDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->shiftingManager->dutiesStore('D', $request, $id);
            $res = [
                'success'=>($managerRes['status'])?true:false,
                'message' => $managerRes['status_message']
            ];
        }else{
            $res = [
                'success'=>false,
                'message' => 'Invalid required data.'
            ];
        }
        return $res;
    }

    public function dutiesDatatable($id)
    {
        $dataTable = $this->shiftingManager->dutiesData();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->editColumn('emp_code', function ($data) {
                return !empty($data['employee'])?$data['employee']['emp_code']:'';
            })
            ->editColumn('emp_name', function ($data) {
                return !empty($data['employee'])?$data['employee']['emp_name']:'';
            })
            ->editColumn('placement', function ($data) {
                return !empty($data['placement'])?$data['placement']['placement_name']:'';
            })
            ->editColumn('designation', function ($data) {
                return !empty($data['designation'])?$data['designation']['designation']:'';
            })
            ->editColumn('formatted_month', function ($data) {
                return date('F', mktime(0, 0, 0, $data['duty_month'], 10));
            })
            ->editColumn('formatted_joining_date', function ($data) {
                return HelperClass::defaultDateTimeFormat($data['joining_date'],'LOCALDATE') ;
            })
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('cms.shifting.duties-edit', $data['employee_duty_id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['employee_duty_id'] ){
                    $optionHtml .= ' <a class="" data-toggle="tooltip" data-placement="top" title="Click to show shift" href="' . route('cms.shifting.shift',  ['employee_duty_id'=>$data['employee_duty_id']]) . '"><i class="bx bx-check-double cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return   HelperClass::getStatusName($data['status']);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /************Start Duty Shifting*************/

    public function dutyShiftingIndex(Request $request)
    {
        $employee=EmployeeDuties::findOrFail($request->get('employee_duty_id'));
        $data=new EmployeeDutyShifting();
        $shift=$this->commonManager->getDutyShiftByEmployeeDuty($employee->duty_year,$employee->duty_month);
        return view('cms.shifting.employee_duty_shifting',
            ['data' => $data,
             'shift'=>$shift,
             'employee'=>$employee
            ]);
    }

    public function dutyShiftingStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'shift_id' => 'required',
                'effective_from_date' => 'required',
                'effective_to_date' => 'required',
            ]);
            $managerRes = $this->shiftingManager->dutyShiftingStore('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function dutyShiftingEdit($id)
    {
        $data=EmployeeDutyShifting::findOrFail($id);
        $employee=EmployeeDuties::findOrFail($data->employee_duty_id);
        $shift=$this->commonManager->getDutyShiftByEmployeeDuty($employee->duty_year,$employee->duty_month);
        return view('cms.shifting.employee_duty_shifting',
            ['data' => $data,
                'shift'=>$shift,
                'employee'=>$employee
            ]);
    }

    public function dutyShiftingUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'shift_id' => 'required',
                'effective_from_date' => 'required',
                'effective_to_date' => 'required',
            ]);
            $managerRes = $this->shiftingManager->dutyShiftingStore('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function dutyShiftingDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->shiftingManager->dutyShiftingStore('D', $request, $id);
            $res = [
                'success'=>($managerRes['status'])?true:false,
                'message' => $managerRes['status_message']
            ];
        }else{
            $res = [
                'success'=>false,
                'message' => 'Invalid required data.'
            ];
        }
        return $res;
    }

    public function dutyShiftingDatatable(Request  $request)
    {
        $dataTable = $this->shiftingManager->dutyShiftingData($request);
//        dd($dataTable);
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $optionHtml =  '<a href="' . route('cms.shifting.shift-edit', $data['emp_duty_shifting_id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
//                $optionHtml .= ' <a class="" data-toggle="tooltip" data-placement="top" title="Click to show offday" href="' . route('cms.offday',  ['duty_shifting_id'=>$data['emp_duty_shifting_id'],'employee_duty_id'=>$data['employee_duty_id']]) . '"><i class="bx bx-check-double cursor-pointer"></i></a>';
                return $optionHtml;
            })
            ->editColumn('shift', function ($data) {
                return !empty($data['shift'])?$data['shift']['shift_name']:'';
            })
            ->editColumn('formatted_effective_from_date', function ($data) {
                return HelperClass::defaultDateTimeFormat($data['effective_from_date'],'LOCALDATE') ;
            })
            ->editColumn('formatted_effective_to_date', function ($data) {
                return HelperClass::defaultDateTimeFormat($data['effective_to_date'],'LOCALDATE') ;
            })
            ->editColumn('status', function ($data) {
                return   HelperClass::getStatusName($data['status']);
            })
            ->escapeColumns([])
            ->make(true);
    }


    /************Off Day *************/
    public function offDayIndex(Request $request)
    {
        $employee=EmployeeDuties::findOrFail($request->get('employee_duty_id'));
        $data=new EmployeeOffDay();
        return view('cms.shifting.employee_offday',
            ['data' => $data,
             'employee'=>$employee,
            ]);
    }

    public function offDayStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'offday_from' => 'required',
                'offday_to' => 'required',
            ]);
            $managerRes = $this->shiftingManager->offDayStore('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function offDayEdit($id)
    {
        $data=EmployeeOffDay::findOrFail($id);
        $employee=EmployeeDuties::findOrFail($data->employee_duty_id);
        return view('cms.shifting.employee_offday',
            ['data' => $data,
              'employee'=>$employee
            ]);
    }

    public function offDayUpdate(Request $request, $id)
    {

        if ($request->isMethod("PUT")) {
            $request->validate([
                'offday_from' => 'required',
                'offday_to' => 'required',
            ]);
            $managerRes = $this->shiftingManager->offDayStore('U', $request, $id);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function offDayDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->shiftingManager->offDayStore('D', $request, $id);
            $res = [
                'success'=>($managerRes['status'])?true:false,
                'message' => $managerRes['status_message']
            ];
        }else{
            $res = [
                'success'=>false,
                'message' => 'Invalid required data.'
            ];
        }
        return $res;
    }

    public function offDayDatatable(Request  $request)
    {
        $dataTable = $this->shiftingManager->offDayData($request);
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $optionHtml =  '<a href="' . route('cms.offday.edit', $data['employee_offday_id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                return $optionHtml;
            })
            ->editColumn('formatted_offday_from', function ($data) {
                return HelperClass::defaultDateTimeFormat($data['offday_from'],'LOCALDATE') ;
            })
            ->editColumn('formatted_offday_to', function ($data) {
                return HelperClass::defaultDateTimeFormat($data['offday_to'],'LOCALDATE') ;
            })
            ->editColumn('status', function ($data) {
                return  HelperClass::getStatusName($data['status']);
            })
            ->escapeColumns([])
            ->make(true);
    }


}
