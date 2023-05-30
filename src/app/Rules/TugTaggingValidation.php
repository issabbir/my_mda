<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TugTaggingValidation implements Rule
{
    protected $tug;
    protected $field=0;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($tug = [])
    {
        $this->tug = $tug;
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
        if ($this->tug != null){
            foreach ($this->tug as $tug){
                if ($tug["tugId"] != "" ){

                    if ($tug["assistanceFrom"]){
                        $this->field = 1;
                        return false;
                    }

                    if ($tug["assistanceTo"]){
                        $this->field = 2;
                        return false;
                    }
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
        $message="";
        switch ($this->field){
            case 1:
                $message = 'Assistance from time is required.';
                break;
            case 2:
                $message = 'Assistance to time is required';
                break;
            default:
                $message = "";
                break;
        }
        return $message;
    }
}
