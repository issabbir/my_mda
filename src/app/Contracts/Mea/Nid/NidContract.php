<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 5/31/2020
 * Time: 2:27 PM
 */
namespace App\Contracts\Mea\Nid;


interface NidContract
{
    public function getNidDataFromServer($nid,$dob);
}
