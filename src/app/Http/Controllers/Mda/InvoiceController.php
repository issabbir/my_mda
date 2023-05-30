<?php


namespace App\Http\Controllers\Mda;


use App\Contracts\Mda\cashCollectionContract;
use App\Contracts\Mda\PilotageContract;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Yajra\DataTables\DataTables;

class InvoiceController extends Controller
{
    protected $pilotageManager;
    protected $cashCollectionManager;

    public function __construct(PilotageContract $pilotageManager, cashCollectionContract $cashCollectionManager)
    {
        $this->pilotageManager = $pilotageManager;
        $this->cashCollectionManager = $cashCollectionManager;
    }
    public function pilotages()
    {
        return view("mda.invoice.pilotages");
    }

    public function pilotageDetail($id)
    {
        $data=$this->pilotageManager->invoiceData($id);
        return view("mda.invoice.pilotage_invoice", ["data"=>json_decode($data[0]["invoice_data"], true), "traceId"=>$data[0]->trace_id]);
    }

    public function pilotagesDatatable()
    {
        $datatable = $this->pilotageManager->approvedPilotages();

        return DataTables::of($datatable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {

                return '<div class="col-md-3"><a href="' . route('invoice-pilotages-view', $data['id']) . '" class=""><span  class="bx bx-show" ></span></a></div>';
            })
            ->editColumn('pilotage_type', function ($data) {
                return (isset($data["pilotage_type"]["name"])) ? $data["pilotage_type"]["name"] : "";
            })
            ->editColumn("vessel_name", function ($data){
                return ($data["foreign_vessel"]["name"]."(".$data["foreign_vessel"]["reg_no"].")");
            })
            ->editColumn('cpa_pilot', function ($data) {
                return (isset($data["cpa_pilot"]["name"])) ? $data["cpa_pilot"]["name"] : "";
            })
            ->editColumn("pilot_borded_at", function ($data){
                return HelperClass::defaultDateTimeFormat($data["pilot_borded_at"],"DATE");
            })
            ->editColumn("pilot_left_at", function ($data){
                return HelperClass::defaultDateTimeFormat($data["pilot_left_at"],"DATE");
            })
            ->make(true);
    }

    //TODO: Cash collection invoice starts here
    public function slips()
    {
        return view("mda.invoice.cash_collections");
    }

    public function slipsDetail($id)
    {
        $data=$this->pilotageManager->invoiceData($id);

        return view("mda.invoice.cash_collections_invoice", ["data"=>json_decode($data[0]["invoice_data"], true)]);
    }

    public function slipsDatatable()
    {
        $dataTable = $this->cashCollectionManager->approvedCollections();

        return DataTables::of($dataTable)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return '<a class="confirm-delete" href="' . route('invoice-cash-collection-view', $data['id']) . '"><i class="bx bx-show cursor-pointer"></i></a>';
            })
            ->editColumn('period_form', function ($data) {
                return HelperClass::defaultDateTimeFormat($data['period_from'], 'date');
            })
            ->editColumn('period_to', function ($data) {
                return  HelperClass::defaultDateTimeFormat($data['period_to'], 'date');
            })
            ->editColumn('total', function ($data) {
                return   $data['river_dues_amount'] +  $data['port_dues_amount']  +  $data['other_dues_amount'] +  $data['vat_amount'] ;
            })
            ->editColumn('status', function ($data) {
                return HelperClass::getStatus(isset($data['status'])?$data['status']:'');
            })
            ->make(true);
    }

    public function downloadInvoice($id)
    {
        $data = $data=$this->pilotageManager->invoiceData($id);
        try {
            $mpdf = new mPDF();
            $mpdf->AddPage('L');
            $html = \View::make("mda.invoice.pdf_pilotage_invoice")
                ->with('data',json_decode($data[0]["invoice_data"], true))
                ->render();
            $mpdf->WriteHTML($html, HTMLParserMode::HTML_BODY);
            $mpdf->Output("pilotage_certificate_invoice_".date('Y_m_d_h_i_a').".pdf", 'I');
        }catch (MpdfException $e){
            echo $e->getMessage();
        }
    }
    //TODO: Cash collection invoice ends here
}
