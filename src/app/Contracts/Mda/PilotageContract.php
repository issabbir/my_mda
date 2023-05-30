<?php
namespace App\Contracts\Mda;


interface PilotageContract
{
    public function pilotageCud($action_type=null, $request=[], $id=null);

    public function pilotageDatatable();

    public function pilotageChangeStatus($action_type, $request, $id);

    public function pilotageInvoiceStore($traceId, $invoiceType, $invoiceData, $sourceTable);

    public function pilotageRegInfoUpdate($pilotageId);

    public function invoiceData($pilotageId);

    public function pilotageDetails($pilotageId);

    public function getPilotageFee($request);

    public function getTugFee($isPrimary, $workLocation, $grt, $assistanceFrom, $assistanceTo);

    public function approvedPilotages();

    public function verifyCertificateDatatable($request);

    public function getDryDockId();
}
