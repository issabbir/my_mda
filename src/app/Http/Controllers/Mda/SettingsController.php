<?php

namespace App\Http\Controllers\Mda;

use App\Contracts\Mda\SettingsContract;
use App\Entities\Mda\Cargo;
use App\Entities\Mda\LCollectionSlipType;
use App\Entities\Mda\LCpaVesselType;
use App\Entities\Mda\LPilotageWorkLocation;
use App\Entities\Mda\LPsScheduleType;
use App\Entities\Mda\LVesselWorkingType;
use App\Entities\Mda\LPilotageType;
use App\Entities\Mda\LTugType;
use App\Entities\Mda\LVesselCondition;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SettingsController extends Controller
{
    public $settingsManager;
    public function __construct(SettingsContract $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

    /************Start collection slip types*************/
    public function collectionSlipTypeCreate(Request $request)
    {
        $data = new LCollectionSlipType();
        return view('mda.collection_slip_type', ['data'=>$data]);
    }

    public function collectionSlipTypeStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
            ]);
            $managerRes = $this->settingsManager->collectionSlipTypeCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function collectionSlipTypeEdit($id)
    {
        return view('mda.collection_slip_type', [
            'data' => LCollectionSlipType::findOrFail($id)
        ]);
    }

    public function collectionSlipTypeUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
            ]);
            $managerRes = $this->settingsManager->collectionSlipTypeCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function collectionSlipTypeDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->collectionSlipTypeCud('D', $request, $id);
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

    public function collectionSlipTypeDatatable($id)
    {
        $dataTable = $this->settingsManager->collectionSlipTypeDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('collection-slip-type-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('collection-slip-type-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }

    /************End collection slip types*************/

    /************Start cpa vessel type****************/
    public function cpaVesselTypeCreate(Request $request)
    {
        $data = new LCpaVesselType();
        return view('mda.cpa_vessel_type', ['data'=>$data]);
    }

    public function cpaVesselTypeStore(Request $request)
    {
        if ($request->isMethod("POST")){
            $request->validate([
                'name' => 'required',
                'status' => 'required'
            ]);

            $managerRes = $this->settingsManager->cpaVesselTypeCud('I', $request);
            if ($managerRes['status']){
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            }else{
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function cpaVesselTypeEdit($id)
    {
        return view('mda.cpa_vessel_type', [
            'data' => LCpaVesselType::findOrFail($id)
        ]);
    }

    public function cpaVesselTypeUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required'
            ]);
            $managerRes = $this->settingsManager->cpaVesselTypeCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function cpaVesselTypeDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->cpaVesselTypeCud('D', $request, $id);
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

    public function cpaVesselTypeDatatable($id)
    {
        $dataTable = $this->settingsManager->cpaVesselTypeDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('cpa-vessel-type-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('cpa-vessel-type-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }
    /************End cpa vessel type****************/
    /************Start pilotage types*************/

    public function pilotageTypeCreate(Request $request)
    {
        $data = new LPilotageType();
        return view('mda.pilotage_type', ['data'=>$data]);
    }

    public function pilotageTypeStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
            ]);
            $managerRes = $this->settingsManager->pilotageTypeCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function pilotageTypeEdit($id)
    {
        return view('mda.pilotage_type', [
            'data' => LPilotageType::findOrFail($id)
        ]);
    }

    public function pilotageTypeUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
            ]);
            $managerRes = $this->settingsManager->pilotageTypeCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function pilotageTypeDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->pilotageTypeCud('D', $request, $id);
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

    public function pilotageTypeDatatable($id)
    {
        $dataTable = $this->settingsManager->pilotageTypeDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('pilotage-type-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('pilotage-type-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }

    /************End Pilotage types*************/
    /************Start Tug types*************/
    public function tugTypeCreate(Request $request)
    {
        $data = new LTugType();
        return view('mda.tug_type', ['data'=>$data]);
    }

    public function tugTypeStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
            ]);
            $managerRes = $this->settingsManager->tugTypeCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function tugTypeEdit($id)
    {
        return view('mda.tug_type', [
            'data' => LTugType::findOrFail($id)
        ]);
    }

    public function tugTypeUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
            ]);
            $managerRes = $this->settingsManager->tugTypeCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function tugTypeDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->tugTypeCud('D', $request, $id);
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

    public function tugTypeDatatable($id)
    {
        $dataTable = $this->settingsManager->tugTypeDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('tug-type-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('tug-type-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }

    /************End tug types*************/


    /************Start cargo types*************/
    public function cargoCreate(Request $request)
    {
        $data = new Cargo();
        return view('mda.cargo', ['data'=>$data]);
    }

    public function cargoStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
            ]);
            $managerRes = $this->settingsManager->cargoCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function cargoEdit($id)
    {
        return view('mda.cargo', [
            'data' => Cargo::findOrFail($id)
        ]);
    }

    public function cargoUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
            ]);
            $managerRes = $this->settingsManager->cargoCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function cargoDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->cargoCud('D', $request, $id);
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

    public function cargoDatatable($id)
    {
        $dataTable = $this->settingsManager->cargoDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('cargo-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('cargo-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }

    /************End cargo types*************/

/************Start Vessel Condition*************/
    public function vesselConditionCreate(Request $request)
    {
        $data = new LVesselCondition();
        return view('mda.vessel_condition', ['data'=>$data]);
    }

    public function vesselConditionStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'title' => 'required',
                'status' => 'required',
                'value_type' => 'required'
            ]);
            $managerRes = $this->settingsManager->vesselConditionCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function vesselConditionEdit($id)
    {
        return view('mda.vessel_condition', [
            'data' => LVesselCondition::findOrFail($id)
        ]);
    }

    public function vesselConditionUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'title' => 'required',
                'status' => 'required',
                'value_type' => 'required'

            ]);
            $managerRes = $this->settingsManager->vesselConditionCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function vesselConditionDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->vesselConditionCud('D', $request, $id);
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

    public function vesselConditionDatatable($id)
    {
        $dataTable = $this->settingsManager->vesselConditionDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('vessel-condition-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('vessel-condition-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }

    /************End Vessel Condition*************/


    /************Start ps pilotage schedule type****************/
    public function psScheduleTypeCreate(Request $request)
    {
        $data = new LPsScheduleType();
        return view('mda.ps_schedule_type', ['data'=>$data]);
    }

    public function psScheduleTypeStore(Request $request)
    {
        if ($request->isMethod("POST")){

            $request->validate([
                'name' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
                'description' => 'required',
                'status' => 'required'
            ]);

            $managerRes = $this->settingsManager->psScheduleTypeCud('I', $request);

            if ($managerRes['status']){
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            }else{
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }

            return $message;
        }
    }

    public function psScheduleTypeEdit($id)
    {
        return view('mda.ps_schedule_type', [
            'data' => LPsScheduleType::findOrFail($id)
        ]);
    }

    public function psScheduleTypeUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
                'status' => 'required'
            ]);
            $managerRes = $this->settingsManager->psScheduleTypeCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function psScheduleTypeDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->psScheduleTypeCud('D', $request, $id);
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

    public function psScheduleTypeDatatable($id)
    {
        $dataTable = $this->settingsManager->psScheduleTypeDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('ps-schedule-type-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('ps-schedule-type-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }
    /************End ps pilotage schedule type****************/

    /************Start Vessel Working type**************/
    public function vesselWorkingTypeCreate(Request $request)
    {
        $data = new LCollectionSlipType();
        return view('mda.vessel_working_type', ['data'=>$data]);
    }

    public function vesselWorkingTypeStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'status' => 'required'
            ]);
            $managerRes = $this->settingsManager->vesselWorkingTypeCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function vesselWorkingTypeEdit($id)
    {
        return view('mda.vessel_working_type', [
            'data' => LVesselWorkingType::findOrFail($id)
        ]);
    }

    public function vesselWorkingTypeUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'status' => 'required'
            ]);
            $managerRes = $this->settingsManager->vesselWorkingTypeCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function vesselWorkingTypeDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->vesselWorkingTypeCud('D', $request, $id);
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

    public function vesselWorkingTypeDatatable($id)
    {
        $dataTable = $this->settingsManager->vesselWorkingTypeDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('vessel-working-type-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('vessel-working-type-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }
    /************End Vessel Working type**************/

    /************Start pilotage Work Location*************/

    public function pilotageWorkLocationCreate(Request $request)
    {
        $data = new LPilotageWorkLocation();
        return view('mda.pilotage_work_location', ['data'=>$data]);
    }

    public function pilotageWorkLocationStore(Request $request)
    {
        if ($request->isMethod("POST")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
            ]);
            $managerRes = $this->settingsManager->pilotageWorkLocationCud('I', $request);
            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function pilotageWorkLocationEdit($id)
    {
        return view('mda.pilotage_work_location', [
            'data' => LPilotageWorkLocation::findOrFail($id)
        ]);
    }

    public function pilotageWorkLocationUpdate(Request $request, $id)
    {
        if ($request->isMethod("PUT")) {
            $request->validate([
                'name' => 'required',
                'status' => 'required'
            ]);
            $managerRes = $this->settingsManager->pilotageWorkLocationCud('U', $request, $id);

            if ($managerRes['status']) {
                $message = redirect()->back()->with('success', $managerRes['status_message']);
            } else {
                $message = redirect()->back()->with('error', $managerRes['status_message'])->withInput();
            }
            return $message;
        }
    }

    public function pilotageWorkLocationDestroy(Request $request, $id)
    {
        if ($id) {
            $managerRes = $this->settingsManager->pilotageWorkLocationCud('D', $request, $id);
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

    public function pilotageWorkLocationDatatable($id)
    {
        $dataTable = $this->settingsManager->pilotageWorkLocationDatatable();
        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use($id) {
                $optionHtml =  '<a href="' . route('p-working-location-edit', $data['id']) . '" class=""><i class="bx bx-edit cursor-pointer"></i></a>';
                if($id != $data['id'] ){
                    $optionHtml .= ' <a class="confirm-delete text-danger" href="' . route('p-working-location-destroy', $data['id']) . '"><i class="bx bx-trash cursor-pointer"></i></a>';
                }
                return $optionHtml;
            })
            ->editColumn('status', function ($data) {
                return isset($data['status']) && $data['status'] == 'A' ? 'Active' : 'Inactive';
            })
            ->make(true);
    }

    /************End Pilotage Work Location*************/
}
