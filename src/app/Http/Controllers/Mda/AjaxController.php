<?php

namespace App\Http\Controllers\Mda;

use App\Contracts\Ewb\Water\AckContract;
use App\Contracts\Secdbms\Watchman\AgencyContract;
use App\Entities\Ewb\Water\BookingMst;
use App\Entities\Ewb\Water\InvoiceMst;
use App\Entities\Ewb\Water\LWaterPipe;
use App\Entities\Ewb\Water\LWaterVessel;
use App\Entities\Ewb\Water\RateChart;
use App\Entities\Ewb\Water\Requisition;
use App\Entities\Secdbms\Watchman\LAgency;
use App\Entities\Secdbms\Watchman\VesselSubType;
use App\Enums\Ewb\Water\DeliveryBy;
use App\Enums\YesNoFlag;
use App\Http\Controllers\Controller;
use App\Contracts\LookupContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Managers\Ewb\Water\AckManager;
use App\Managers\LookupManager;
use App\Managers\Pmis\Employee\EmployeeManager;
use App\Managers\Secdbms\Watchman\AgencyManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    /** @var EmployeeManager */
    private $employeeManager;

    /** @var LookupManager */
    private $lookupManager;

    /** @var AgencyManager */
    private $agencyManager;

    /** @var AckManager  */
    private $ackManager;

    /**
     * AjaxController constructor.
     * @param EmployeeContract $employeeManager
     * @param LookupContract $lookupManager
     * @param AgencyContract $agencyManager
     * @param AckContract $ackManager
     */
    public function __construct(EmployeeContract $employeeManager, LookupContract $lookupManager, AgencyContract $agencyManager, AckContract $ackManager)
    {
        $this->employeeManager = $employeeManager;
        $this->lookupManager = $lookupManager;
        $this->agencyManager = $agencyManager;
        $this->ackManager = $ackManager;
    }

    public function employees(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findEmployeeCodesBy($searchTerm);

        return $employees;
    }

    public function employeesWithName(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findEmployeesWithNameBy($searchTerm);

        return $employees;
    }

    public function employeesWithDept(Request $request,$empDept)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findDeptWiseEmployeeCodesBy($searchTerm,$empDept);

        return $employees;
    }

    public function employee(Request $request, $empId)
    {
        return $this->employeeManager->findEmployeeInformation($empId);
    }

    public function districts(Request $request, $divisionId)
    {
        $districts = [];

        if($divisionId) {
            $districts = $this->lookupManager->findDistrictsByDivision($divisionId);
        }

        $html = view('ajax.districts')->with('districts', $districts)->render();

        return response()->json(array('html'=>$html));
    }

    public function thanas(Request $request, $districtId)
    {
        $thanas = [];

        if($districtId) {
            $thanas = $this->lookupManager->findThanasByDistrict($districtId);
        }

        $html = view('ajax.thanas')->with('thanas', $thanas)->render();

        return response()->json(array('html'=>$html));
    }

    public function branches(Request $request, $bankId)
    {
        $branches = [];

        if($bankId) {
            $branches = $this->lookupManager->findBranchesByBank($bankId);
        }

        $html = view('ajax.branches')->with('branches', $branches)->render();

        return response()->json(array('html'=>$html));
    }

    public function agencies(Request $request)
    {
        $searchTerm = $request->get('term');

        return $this->agencyManager->findAgenciesBy($searchTerm);
    }

    public function agency(Request $request, $agencyId)
    {
        return LAgency::find($agencyId);
    }

    public function requisitions(Request $request)
    {
        $exclude = $request->get('exclude');
        $jsonDecodedExcludeRequisitionId = json_decode($exclude) ?? [];

        $excludeRequisitionIds = array_filter($jsonDecodedExcludeRequisitionId);
        $searchTerm = $request->get('term');

        $searchTermQuery = '';
        $excludeRequisitionQuery = '';
        $whereClauseParams = [];

        if ($searchTerm) {
            $searchTermQuery = <<<SEARCH_TERM_CLAUSE
    AND wr.req_no LIKE :req_no
SEARCH_TERM_CLAUSE;

            $whereClauseParams['req_no'] = '%'.$searchTerm.'%';
        }

        if ($excludeRequisitionIds) {
            $inClauseValue = "'".implode("', '", $excludeRequisitionIds)."'";
            $excludeRequisitionQuery = <<<EXCLUDED_REQUISITION_CLAUSE
    OR wr.req_id IN ($inClauseValue)
EXCLUDED_REQUISITION_CLAUSE;
        }

        $query = <<<QUERY
SELECT *
FROM
  (
    SELECT wr.req_id,
           wr.req_no
    FROM W_REQUISITION wr
    WHERE NOT EXISTS
      (
        SELECT wbm.req_id
        FROM W_BOOKING_MST wbm
        WHERE wbm.req_id = wr.req_id
      )
       $searchTermQuery $excludeRequisitionQuery 
    AND wr.active_yn = 'Y'
    ORDER BY LENGTH (wr.req_no) ASC, wr.req_no ASC
  )
WHERE ROWNUM <= 10
QUERY;

        $requisitions = DB::select($query, $whereClauseParams);

        return $requisitions;
    }

    public function requisition(Request $request, $id)
    {
        $query = <<<QUERY
SELECT wr.req_id as req_id,
       wr.req_no as req_no,
       wr.req_date as req_date,
       wr.req_supply_date as req_supply_date,
       la.agency_name,
       wr.vessel_name,
       wvt.vessel_type,
       wvst.vessel_sub_type,
       lwda.delivery_area,
       wr.water_qty
FROM
  W_REQUISITION wr
    LEFT JOIN L_W_DELIVERY_AREA lwda ON wr.DELIVERY_AREA_ID = lwda.DELIVERY_AREA_ID
    LEFT JOIN SECDBMS.L_AGENCY la ON wr.SHIPPING_AGENCY_ID = la.AGENCY_ID
    LEFT JOIN SECDBMS.WM_VESSEL_TYPE wvt ON wr.VESSEL_TYPE_ID = wvt.VESSEL_TYPE_ID
    LEFT JOIN SECDBMS.WM_VESSEL_SUB_TYPE wvst ON wr.VESSEL_SUB_TYPE_ID = wvst.VESSEL_SUB_TYPE_ID
    WHERE
    wr.req_id = :req_id
    AND ROWNUM <= 1
QUERY;

        $requisition = DB::selectOne($query, ['req_id' => $id]);

        if($requisition) {
            $jsonEncoded = json_encode($requisition);
            $requisitionArray = json_decode($jsonEncoded, true);

            return $requisitionArray;
        }

        return [];
    }

    public function vesselSubTypes(Request $request, $vesselTypeId)
    {
        $subTypes = [];

        if($vesselTypeId) {
            $subTypes = VesselSubType::where('vessel_type_id', $vesselTypeId)->get();
        }

        $html = view('water.ajax.vessel_sub_types')->with('subTypes', $subTypes)->render();

        return response()->json(array('html'=>$html));
    }

    public function vesselsOrPipes(Request $request, $typeId)
    {
        $exclude = $request->get('exclude');
        $jsonDecodedExcludeId = json_decode($exclude);
        if($jsonDecodedExcludeId) {
            $excludeIds = array_filter($jsonDecodedExcludeId);
        } else {
            $excludeIds = [];
        }

        $searchTerm = $request->get('term');

        $collections = [];

        if(!$typeId) {
            return $collections;
        }

        if($typeId == YesNoFlag::YES) {
            $where = [
                [DB::raw('LOWER(water_vessel_name)'), 'like', strtolower('%'.trim($searchTerm).'%')],
                [DB::raw('LOWER(water_vessel_no)'), 'like', strtolower('%'.trim($searchTerm).'%')],
                ['active_yn', '=', YesNoFlag::YES],
            ];

            $collections = LWaterVessel::where($where);

            if($excludeIds) {
                $collections = $collections->whereNotIn('water_vessel_id', $excludeIds);
            }

            $collections = $collections->orderBy('water_vessel_name', 'ASC')
                ->limit(10)
                ->select('water_vessel_id AS entity_id', 'water_vessel_no AS entity_no', 'water_vessel_name AS entity_name', 'capacity')
                ->get();

        } else if($typeId == YesNoFlag::NO) {
            $where = [
                [DB::raw('LOWER(water_pipe_name)'), 'like', strtolower('%'.trim($searchTerm).'%')],
                [DB::raw('LOWER(water_pipe_no)'), 'like', strtolower('%'.trim($searchTerm).'%')],
                ['active_yn', '=', YesNoFlag::YES],
            ];

            $collections = LWaterPipe::where($where);

            if($excludeIds) {
                $collections = $collections->whereNotIn('water_pipe_id', $excludeIds);
            }

            $collections = $collections->orderBy('water_pipe_name', 'ASC')
                ->limit(10)
                ->select('water_pipe_id AS entity_id', 'water_pipe_no AS entity_no', 'water_pipe_name AS entity_name')
                ->get();
        }

        return $collections;
    }

    public function vesselOrPipe(Request $request, $typeId, $id)
    {
        $object = [];

        if(!$typeId) {
            return $object;
        }

        if($typeId == YesNoFlag::YES) {
            $object = LWaterVessel::select(
                [
                    'water_vessel_id AS entity_id',
                    'water_vessel_no AS entity_no',
                    'water_vessel_name AS entity_name',
                    'capacity'
                ]
            )->find($id);

        } else if($typeId == YesNoFlag::NO) {
            $object = LWaterPipe::select(
                [
                    'water_pipe_id AS entity_id',
                    'water_pipe_no AS entity_no',
                    'water_pipe_name AS entity_name'
                ]
            )->find($id);
        }

        return $object;
    }

    public function availableBookings(Request $request)
    {
        $exclude = $request->get('exclude');
        $jsonDecodedExcludeBookingId = json_decode($exclude) ?? [];
        $excludeBookingIds = array_filter($jsonDecodedExcludeBookingId);

        $searchTerm = $request->get('term');
        $bookings = $this->ackManager->findAvailableBookingsBy($searchTerm, $excludeBookingIds);

        return $bookings;
    }

    public function booking(Request $request, $bookingId)
    {
        $booking = $this->ackManager->findBooking($bookingId);

        return [
            'booking' => $booking['booking'],
            'bookingDetails' => $booking['bookingDetails']
        ];
    }

    public function ackBookings(Request $request)
    {
        $searchTerm = $request->get('term');
        $bookings = $this->ackManager->findAckBookingsBy($searchTerm);

        return $bookings;
    }

    public function ackBooking(Request $request, $bookingId)
    {
        $booking = $this->ackManager->findAckBooking($bookingId);

        $bookingDetailsRowHtml = '';
        if(isset($booking['bookingDetails'])) {
            $bookingDetailsRowHtml = view('water.ajax.booking_details_rows')->with('bookingDetails', $booking['bookingDetails'])->render();
        }

        return [
            'booking' => $booking['booking'],
            'bookingDetails' => $booking['bookingDetails'],
            'bookingDetailsRowHtml' => $bookingDetailsRowHtml
        ];
    }

    public function bookings(Request $request)
    {
        $searchTerm = $request->get('term');

        return BookingMst::where(
            [
                ['booking_no', 'like', ''.$searchTerm.'%'],
            ]
        )->orderBy('booking_no', 'ASC')->limit(10)->get(['booking_mst_id', 'booking_no']);
    }

    public function reportRequisitions(Request $request)
    {
        $searchTerm = $request->get('term');

        return Requisition::where(
            [
                ['req_no', 'like', ''.$searchTerm.'%'],
            ]
        )->orderBy('req_no', 'ASC')->limit(10)->get(['req_id', 'req_no']);
    }

    public function invoices(Request $request)
    {
        $searchTerm = $request->get('term');

        return InvoiceMst::where(
            [
                ['invoice_no', 'like', ''.$searchTerm.'%'],
            ]
        )->orderBy('invoice_no', 'ASC')->limit(10)->get(['invoice_mst_id', 'invoice_no']);
    }
}
