<?php
namespace App\Contracts\Cms;


interface VesselContract
{
    public function vesselStore($action_type=null, $request = [], $id=null);
    public function vesselData();
    public function fuelConsumptionMstStore($action_type=null, $request = [], $id=null);
    public function fuelConsumptionDtlStore($action_type=null, $request = [], $id=null);
    public function fuelConsumptionData($request = []);
    public function vesselEngineMappingStore($action_type=null, $request = [], $id=null);
    public function vesselEngineMappingData($request = []);
    public function consumptionApprovalStore($request=[],$id=null);
    public function fuelConsumptionStoreWithApproval($action_type=null, $request = [], $id=null);
}
