<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 5/31/2020
 * Time: 2:27 PM
 */
namespace App\Contracts\Mea\Vms;

interface CommonContract
{
    public function commonDropDownLookupsList($look_up_name = null,$column_selected = null,$fetch_single_colid = null);
    public function loadDecisionDropdown($column_selected = null);
    public function findInsertedData($pkgFunction,$primaryId = null,$multipleRow=null);
}