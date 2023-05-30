<?php
/**
 * Created by PhpStorm.
 * User: ashraf
 * Date: 1/26/20
 * Time: 12:46 PM
 */

namespace App\Enums;


class BillTypeEnum
{
    public const WATER_BILL_PIPE_LINE = '20';
    public const WATER_BILL_OUTER = '23';
    public const FORKLIFT = '21';
    public const JETTY_BILL = '5';
    public const Ligheter_Bill = '1';
    public const Pilotage_Bill_Local = '34';
    public const Jetty_Bill_Local = '35';
    public const FIRE_BILL = '36';
    public const TUG_BILL = '24';
    public const TUG_CANCEL_BILL = '28';
    public const NAVY_BILL = '2';
    public const LIGHTER_BILL_SWING_MOORING = '37';
    public const LIGHTER_BILL_FIXED_MOORING = '38';
    public const LIGHTER_BILL_OIL_FIXED_MOORING = '39';

    /*30
31
33
*/
    public const PILOTAGE_INWARD_BILL = '30';
    public const PILOTAGE_SHIFTING_BILL = '31';
    public const PILOTAGE_OUTWARD_BILL = '33';
    public const PILOTAGE_BILL = '4';

    public const PILOTAGE_BILl=['30','31','33'];

    public const PORT_DUES_BILL=25;
    public const SCRAP_VESSEL_BILL=40;

    public const PORT_DUES_BILL_SERVICE_CODE=['407001','407002','407003'];



    public const RATE_BASIS=['NOS','QTR','DIM','LTR','HRS','GRT'];


    public const PILOTAGE_INWARD_SERVICE = '1';
    public const PILOTAGE_SHIFTING_SERVICE = '2';
    public const PILOTAGE_OUTWARD_SERVICE = '3';
}
