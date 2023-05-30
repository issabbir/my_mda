<?php

namespace App\Rules;

use DateTime;
use Illuminate\Contracts\Validation\Rule;

class DateTimeRange implements Rule
{
    protected $from, $to, $type, $timeInterval, $dayInterval, $dTLError=false,$dTEError=false, $timeLError = false, $timeSError= false, $timeEError= false, $dayLError= false, $daySError= false, $dayEError= false;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($from, $to, $type, $timeInterval=null, $dayInterval=null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->type = $type;
        $this->timeInterval = $timeInterval;
        $this->dayInterval = $dayInterval;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->type == "t"){
            //TODO:write condition for time picker
        }
        if ($this->type == "d"){
            //TODO:write condition for date picker

        }
        if ($this->type == "dt"){
            $startDateTime = new DateTime($this->from);
            $endDateTime = new DateTime($this->to);

            if ($startDateTime > $endDateTime){
                $this->dTLError = true;
                return false;
            }

            if ($startDateTime == $endDateTime){
                $this->dTEError = true;
                return false;
            }

            if (!empty($this->dayInterval)){

                $startEndDiff = $startDateTime->diff($endDateTime);

                if ($startEndDiff->d >  $this->dayInterval){
                    $this->dayLError = true;
                    return false;
                }

                if ($startEndDiff->d <  $this->dayInterval){
                    $this->daySError = true;
                    return false;
                }
            }

            if (!empty($this->timeInterval)){

                $timeInterv = new DateTime($this->timeInterval);
                $timeInterv = $timeInterv->format("H:i:s");

                $startEndDiff = $startDateTime->diff($endDateTime);
                $startEndDiff = $startEndDiff->format("H:i:s");

                if ($startEndDiff >  $timeInterv){
                    $this->timeLError = true;
                    return false;
                }

                if ($startEndDiff <  $timeInterv){
                    $this->timeSError = true;
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
       /* $dTLError=false,
        $dTEError=false,
        $timeLError = false,
        $timeSError= false,
        $timeEError= false,
        $dayLError= false,
        $daySError= false,
        $dayEError= false;*/

        if ($this->dTLError){
            return "End date-time can't be smaller then Start date-time. ";
        }

        if ($this->dTEError){
            return "End date-time and Start date-time can't be equal. ";
        }

        if ($this->dayLError){
            return "Days interval can't be larger than ".$this->dayInterval." days";
        }

        if ($this->daySError){
            return "Days interval can't be smaller than ".$this->dayInterval." days";
        }

        if ($this->timeLError){
            return "Time interval can't be larger than ".$this->timeInterval;
        }

        if ($this->timeSError){
            return "Time interval can't be smaller than ".$this->timeInterval;
        }
    }
}
