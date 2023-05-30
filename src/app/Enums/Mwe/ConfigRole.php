<?php
/**
 * Created by PhpStorm.
 * User: ashraf
 * Date: 1/26/20
 * Time: 12:46 PM
 */

namespace App\Enums\Mwe;



abstract class  ConfigRole
{
    public const can_be_edit_maintenance_req = array('1','3');
    public const can_be_assign_inspector = array('2','4');
    public const can_be_auth_maintenance_req=array('1','2','3','4','12');
    public const can_be_make_inspection=array('4','5','6','7','8','9');
    public const can_be_add_inspection_job = array('4', '5', '7');
    public const can_be_add_slipway = array('6');
    public const can_be_inspection_authorized = array('5', '6', '7');
    public const can_be_assigned_workshop = array('8', '9');
    public const con_not_be_work_in_inspection = array('10','11');
    public const can_be_make_requisition=array('9','10','11');
    public const can_not_edit_requisition_master=array('A','C');
    public const can_update_requisition_items=array('A','C');

    public const can_be_send_job_order_to_workshop=array('8','9');
}
