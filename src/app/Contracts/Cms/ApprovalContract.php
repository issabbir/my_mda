<?php

namespace App\Contracts\Cms;


interface ApprovalContract
{
    public function authorized($request=[]);
    public function sendToApproval($request=[]);
    public function fuelReceivingProcess($request=[]);

}
