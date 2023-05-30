<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 5/31/2020
 * Time: 2:27 PM
 */
namespace App\Contracts\Mea\Brta;


interface BrtaContract
{
    public function matchVehicle($regNoNumberFieldOnly);
    public function getvehiclewithid($regNo);


    public function getCpaDataFromBrta($regNo);

   // public function getVehiclePart($matchString);
   // public function getVehiclePart($matchString);
}
