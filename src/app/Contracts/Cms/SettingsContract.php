<?php
namespace App\Contracts\Cms;


interface SettingsContract
{
    public function fuelTypesCud($action_type=null, $request = [], $id=null);
    public function placementCud($action_type=null, $request = [], $id=null);
    public function shiftingCud($action_type=null, $request = [], $id=null);
    public function vesselEngineTypeCud($action_type=null, $request = [], $id=null);
    public function fuelTypesData();
    public function placementData();
    public function shiftingData();
    public function vesselEngineTypeData();
}
