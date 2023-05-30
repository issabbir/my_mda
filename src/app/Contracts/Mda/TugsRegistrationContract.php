<?php


namespace App\Contracts\Mda;


interface TugsRegistrationContract
{
    public function tugsCud($action_type=null, $request=[], $id=null);

    public function tugsDatatable();

}
