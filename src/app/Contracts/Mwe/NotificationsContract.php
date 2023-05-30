<?php
namespace App\Contracts\Mwe;

interface NotificationsContract
{

    public function count();
    public function allData();
    public function recentNotifications();
    public function seenNotifications($id);

}
