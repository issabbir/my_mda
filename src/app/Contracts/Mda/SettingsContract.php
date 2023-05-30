<?php
namespace App\Contracts\Mda;


interface SettingsContract
{
    public function collectionSlipTypeCud($action_type=null, $request = [], $id=null);
    public function collectionSlipTypeDatatable();

    public function pilotageTypeCud($action_type=null, $request = [], $id = null);
    public function pilotageTypeDatatable();

    public function pilotageWorkLocationCud($action_type=null, $request=[], $id = null);
    public function pilotageWorkLocationDatatable();

    public function tugTypeCud($action_type=null, $request = [], $id = null);
    public function tugTypeDatatable();

    public function cargoCud($action_type=null, $request = [], $id = null);
    public function cargoDatatable();

    public function vesselConditionCud($action_type=null, $request = [], $id = null);
    public function vesselConditionDatatable();

    public function cpaVesselTypeCud($action_type=null, $request = [], $id = null);
    public function cpaVesselTypeDatatable();

    public function psScheduleTypeCud($action_type=null, $request = [], $id = null);
    public function psScheduleTypeDatatable();

    public function vesselWorkingTypeCud($action_type=null, $request = [], $id=null);
    public function vesselWorkingTypeDatatable();
}
