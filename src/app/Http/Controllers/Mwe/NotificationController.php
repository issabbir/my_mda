<?php

namespace App\Http\Controllers\Mwe;

use App\Managers\Mwe\NotificationsManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{
    protected $notificationsManager;
    public function __construct(NotificationsManager $notificationsManager)
    {
        $this->notificationsManager = $notificationsManager;
    }

    public function index() {
        $this->notificationsManager->updateNotifications();
        $data = $this->notificationsManager->allData();
        return view('notification.index', ['data'=>$data]);
    }

    public function ajaxNotificationCount()
    {
       return json_encode([
           'count' => $this->notificationsManager->count(),
           'data' => $this->notificationsManager->recentNotifications(),
       ]);
    }

}
