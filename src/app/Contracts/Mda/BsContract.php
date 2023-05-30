<?php
namespace App\Contracts\Mda;


interface BsContract
{
    public function bsCud($action_type=null, $request = [], $id=null);
    public function bsDatatable();
}
