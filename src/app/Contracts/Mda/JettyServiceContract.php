<?php


namespace App\Contracts\Mda;


interface JettyServiceContract
{
    public function jettyServiceCud($request,$action_type = null,$id = null);

    public function jettyServiceDatatable();

}
