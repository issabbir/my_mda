<?php
namespace App\Contracts\Mda;



interface SmContract
{
    public function swingMooringsCud($action_type=null, $request = [], $id=null);
    public function swingMooringsDatatable();

    public function mooringVisitsCud($action_type=null, $request = [], $id=null);
    public function mooringVisitsDatatable($request);
    public function smInsApprovalDatatable( $request);

    public function mooringVisitChangeStatus($action_type=null, $request = [], $id=null);

}
