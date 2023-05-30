<?php

namespace App\Http\Controllers\Cms;

use App\Contracts\Cms\SettingsContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Entities\Cms\LFuelType;
use App\Entities\Cms\LPlacement;
use App\Entities\Cms\LVesselEngineType;
use App\Entities\Cms\Shifting;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Pmis\Employee\EmployeeManager;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SettingsController extends Controller
{
    public $settingsManager;
    public $employeeManager;

    public function __construct(SettingsContract $settingsManager,EmployeeContract $employeeManager)
    {
        $this->settingsManager = $settingsManager;
        $this->employeeManager = $employeeManager;
    }

    /************Start fuel type setup*************/
    public function fuelIndex(Request $request)
    {
        $data = new LFuelType();
        return view('cms.fuel_types', ['data'=>$data]);
    }

    public function fuelStore(Request $request)
    {

        if ($request->isMethod("POST")) {
            $request->validate([
                'fuel_type_name' => 'required',
                'status' => 'required',
            ]);
            $managerRes = $this->settingsManager->fuelTypesCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function fuelEdit($id)
    {
        return view('cms.fuel_types', [
            'data' => LFuelType::findOrFail($id)
        ]);
    }

    public function fuelUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'fuel_type_name' => 'required',
                'status' => 'required',
            ]);
            $managerRes = $this->settingsManager->fuelTypesCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function fuelDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->fuelTypesCud('D', $request, $id);
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

    public function fuelDatatable($id)
    {
        $dataTable = $this->settingsManager->fuelTypesData();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('cms.setting.fuel-edit', $data['fuel_type_id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['fuel_type_id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('cms.setting.fuel-destroy', $data['fuel_type_id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return   HelperClass::getStatusName($data['status']);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /************Start placement setup*************/

    public function placementIndex(Request $request)
    {
        $data = new LPlacement();
        return view('cms.placement',
            ['data' => $data,
            ]);
    }

    public function placementStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'placement_name' => 'required',
            ]);
            $managerRes = $this->settingsManager->placementCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function placementEdit($id)
    {
        return view('cms.placement', [
            'data' => LPlacement::findOrFail($id),
        ]);
    }

    public function placementUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'placement_name' => 'required',
            ]);
            $managerRes = $this->settingsManager->placementCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function placementDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->placementCud('D', $request, $id);
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

    public function placementDatatable($id)
    {
        $dataTable = $this->settingsManager->placementData();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('cms.setting.placement-edit', $data['placement_id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['placement_id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('cms.setting.placement-destroy', $data['placement_id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return   HelperClass::getStatusName($data['status']);
            })
            ->escapeColumns([])
            ->make(true);
    }



    /************Start vessel engine type setup*************/
    public function vesselEngineTypeIndex(Request $request)
    {
        $data = new LVesselEngineType();
        return view('cms.vessel_engine_type',
            ['data' => $data
            ]);
    }

    public function vesselEngineTypeStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'engine_name' => 'required',
            ]);
            $managerRes = $this->settingsManager->vesselEngineTypeCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function vesselEngineTypeEdit($id)
    {

        $data=LVesselEngineType::findOrFail($id);
        return view('cms.vessel_engine_type',
            ['data' => $data]);
    }

    public function vesselEngineTypeUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'engine_name' => 'required',
            ]);
            $managerRes = $this->settingsManager->vesselEngineTypeCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function vesselEngineTypeDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->vesselEngineTypeCud('D', $request, $id);
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

    public function vesselEngineTypeDatatable($id)
    {
        $dataTable = $this->settingsManager->vesselEngineTypeData();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('cms.setting.vessel-engine-edit', $data['engine_id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['engine_id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('cms.setting.vessel-engine-destroy', $data['engine_id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return  HelperClass::getStatusName($data['status']);
            })
            ->escapeColumns([])
            ->make(true);
    }


    /************Start shifting setup*************/
    public function shiftingIndex(Request $request)
    {
        $data = new Shifting();
        return view('cms.shifting',
            ['data' => $data
            ]);
    }

    public function shiftingStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'shift_name' => 'required',
                'effective_from_date' => 'required',
                'effective_to_date' => 'required',
                'shifting_start_time' => 'required',
                'shifting_end_time' => 'required',
            ]);
            $managerRes = $this->settingsManager->shiftingCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function shiftingEdit($id)
    {
        return view('cms.shifting', [
            'data' => Shifting::findOrFail($id)
        ]);
    }

    public function shiftingUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'shift_name' => 'required',
                'effective_from_date' => 'required',
                'effective_to_date' => 'required',
                'shifting_start_time' => 'required',
                'shifting_end_time' => 'required',
            ]);
            $managerRes = $this->settingsManager->shiftingCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function shiftingDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->shiftingCud('D', $request, $id);
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

    public function shiftingDatatable($id)
    {
        $dataTable = $this->settingsManager->shiftingData();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('cms.setting.shifting-edit', $data['shifting_id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['shifting_id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('cms.setting.shifting-destroy', $data['shifting_id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('formatted_effective_from_date', function ($data) {
                return HelperClass::defaultDateTimeFormat($data['effective_from_date'],'LOCALDATE') ;
            })
            ->editColumn('formatted_effective_end_date', function ($data) {
                return HelperClass::defaultDateTimeFormat($data['effective_to_date'],'LOCALDATE') ;
            })
            ->editColumn('status', function ($data) {
                return  HelperClass::getStatusName($data['status']);
            })
            ->escapeColumns([])
            ->make(true);
    }



}
