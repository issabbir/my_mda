<?php
namespace App\Contracts\Cms;

interface NotificationsContract
{

    public function count();
    public function allData();
    public function recentNotifications();
    public function seenNotifications($id);

}
