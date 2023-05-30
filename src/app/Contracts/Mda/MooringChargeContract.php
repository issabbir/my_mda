<?php
namespace App\Contracts\Mda;


interface MooringChargeContract
{
    public function mooringChargeCud($action_type=null, $request = [], $id=null);
    public function mooringChargeDatatable();

    public function approvedCollections();

    public function mooringChargeDetail($id);
}
