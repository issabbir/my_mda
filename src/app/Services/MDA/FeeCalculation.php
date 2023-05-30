<?php


namespace App\Services\MDA;


class FeeCalculation
{
    private $pilotageType;
    private $guptaLake;
    private $scheduleType;
    private $grt;
    private $vesselId;
    private $pilotagePrice;
    private $guptaPrice;
    private $nightNavigationPrice;
    private $totalPilotagePrice;

    private $shiftingPrice;
    private $totalShiftingPrice;

    private $tugType;
    private $tugWorkingType; //Pilotage movement, rent, out of berthing area.
    private $tugWorkingLocation; //Inside port area, outside port area.
    private $tugPrice;
    private $tugAssistanceFromTime;
    private $tugAssistanceToTime;

    public function pilotage_fee_calculation($pilotageType = null, $lake = null, $scheduleType = null, $grt = null, $vesselId = null){
        $this->pilotageType = $pilotageType;
        $this->guptaLake = $lake;
        $this->scheduleType = $scheduleType;
        $this->grt = $grt;
        $this->vesselId = $vesselId;
        $fee = [];

        $this->pilotagePrice = ceil($this->grt/1000) * 35.75;

        if($this->pilotageType == "INWORD" || $this->pilotageType == "OUTWORD"){
            $this->pilotagePrice = ($this->pilotagePrice > 357.50) ? $this->pilotagePrice : 357.50;

            $this->totalPilotagePrice = $this->pilotagePrice;
        }

        if ($this->guptaLake == "TRUE"){
            $this->guptaPrice = ($this->pilotagePrice*50)/100;

            $this->totalPilotagePrice += $this->guptaPrice;
        }

        if ($this->scheduleType == "NIGHT"){
            $this->nightNavigationPrice = ($this->grt < 5000) ? 18.50 : (($this->grt < 10000) ? 34.00 : 43.00);

            $this->totalPilotagePrice += $this->nightNavigationPrice;
        }

        $fee = array("pilotagePrice"=> $this->pilotagePrice,
                        "guptaLakePrice" => $this->guptaPrice,
                        "nightNavigationPrice" => $this->nightNavigationPrice,
                        "totalPrice" => $this->totalPilotagePrice);

        return $fee;
    }

    public function shifting_fee_calculation($scheduleType)
    {
        $this->scheduleType = $scheduleType;
        $this->shiftingPrice = ($this->scheduleType == "NIGHT")? 59.60 : 29.80;

        $this->totalShiftingPrice = $this->shiftingPrice+$this->guptaPrice;

        return $this->totalShiftingPrice;
    }

    public function tug_fee_calculation($tugType, $tugWorkingType, $tugWorkingLocation,$assistanceFrom, $assistanceTo, $grt)
    {
        $this->tugType = $tugType;
        $this->tugWorkingType = $tugWorkingType;
        $this->grt = $grt;
        $this->tugWorkingLocation = $tugWorkingLocation;
        $this->tugAssistanceFromTime = $assistanceFrom;
        $this->tugAssistanceToTime = $assistanceTo;

        if ($this->tugType == "PRIMARY" && $this->tugWorkingType == "PILOTAGE MOVEMENT" ){
            $this->tugPrice = (($this->grt == 200 || $this->grt > 200) && ($this->grt == 1000 || $this->grt < 1000)) ? 158.00 : (($this->grt > 1000 && ($this->grt == 5000 || $this->grt < 5000)) ? 316.00 : 632.00);
        }elseif ($this->tugWorkingType == "RENT" && $this->tugWorkingLocation == "INSIDE PORT"){
           $totalHours = ceil((strtotime($this->tugAssistanceToTime) - strtotime($this->tugAssistanceFromTime))/(60*60));
           $this->tugPrice = 158.00 * $totalHours;
        }else{
            $this->tugPrice = 316.00;
        }

        return $this->tugPrice;
    }

}
