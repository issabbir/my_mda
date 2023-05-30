<?php
namespace App\Contracts\Mda;


interface LocalVesselContract
{
    public function localVesselCud($action_type=null, $request = [], $id=null);
    public function localVesselDatatable();


}
