<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 5/31/2020
 * Time: 2:30 PM
 */
namespace App\Managers\Mea\Vms;

use App\Contracts\Mea\Vms\CommonContract;
use Illuminate\Support\Facades\DB;

class CommonManager implements CommonContract
{
    public function commonDropDownLookupsList($look_up_name = null,$column_selected = null,$fetch_single_colid = null){
        if($fetch_single_colid){
            $query = "Select ".$look_up_name."('".$fetch_single_colid."') from dual" ;
        }else{
            $query = "Select ".$look_up_name."() from dual" ;
        }
        $entityList = DB::select($query);
        $entityOption = [];
        $entityOption[] = "<option value=''>Please select an option</option>";
        foreach ($entityList as $item) {
            $entityOption[] = "<option value='".$item->pass_value."'".($column_selected == $item->pass_value ? 'selected':'').">".$item->show_value."</option>";
        }
        return $entityOption;
        //return response()->json($entityOption);
    }

    public function loadDecisionDropdown($column_selected = null){
        $query = array(
            array(
                'pass_value'=>'Y',
                'show_value'=>'Yes'
            ),
            array(
                'pass_value'=>'N',
                'show_value'=>'No'
            ),
        ) ;

        $entityList = $query;
        $entityOption = [];
        $entityOption[] = "<option value=''>Please select an option</option>";
        foreach ($entityList as $item) {
            $entityOption[] = "<option value='".$item['pass_value']."'".($column_selected == $item['pass_value'] ? 'selected':'').">".$item['show_value']."</option>";
        }
        return $entityOption;
    }

    public function findInsertedData($pkgFunction,$primaryId = null,$multipleRow=null){
        if($primaryId){
            $querys = "SELECT ".$pkgFunction."('".$primaryId."') from dual" ;
        }else{
            $querys = "Select ".$pkgFunction."() from dual" ;
        }
        $entityList = DB::select($querys);
        if(isset($primaryId) && !isset($multipleRow)){
            $array1d = $entityList[0];
            return $array1d;
        }
        return $entityList;
    }

}
