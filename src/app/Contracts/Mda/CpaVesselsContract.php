<?php


namespace App\Contracts\Mda;


interface CpaVesselsContract
{
    public function cpaVesselsCud($action_type = null, $request = [], $id = null);

    public function cpaVesselsDatatable();
}
