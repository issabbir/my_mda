<?php
namespace App\Helpers;

use App\Entities\Security\Menu;
use App\Enums\ModuleInfo;
use App\Managers\Authorization\AuthorizationManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CmsClass
{

    public $id;
    public $links;

    /**
     * @return mixed
     */
    public static function menuSetup()
    {
        if (Auth::user()->hasGrantAll()) {
            $moduleId = ModuleInfo::VMS_MODULE_ID;
            $menus = Menu::where('module_id', $moduleId)->orderBy('menu_order_no')->get();

            return $menus;
        } else {
            $allMenus = Auth::user()->getRoleMenus();
            $menus = [];

            if($allMenus) {
                foreach($allMenus as $menu) {
                    if($menu->module_id == ModuleInfo::VMS_MODULE_ID) {
                        $menus[] = $menu;
                    }
                }
            }

            return $menus;
        };
    }

    public static function getActiveRouteNameWrapping($routeName)
    {
		if (in_array($routeName, ['driver-enlist-edit'])) {
            return 'driver-enlist-index';
        } else if (in_array($routeName, ['vehicle-info-edit'])) {
            return 'vehicle-info-index';
        } else if (in_array($routeName, ['vehicle-assign-edit'])) {
            return 'vehicle-assign-index';
        } else if (in_array($routeName, ['fuel-consumption-edit'])) {
            return 'fuel-consumption-index';
        } else if (in_array($routeName, ['maintenance-edit'])) {
            return 'maintenance-index';
        } else if (in_array($routeName, ['workshop-edit'])) {
            return 'workshop-index';
        }  else if (in_array($routeName, ['services-edit'])) {
            return 'services-index';
        }  else if (in_array($routeName, ['vehicle-rent-edit'])) {
            return 'vehicle-rent-index';
        }  else if (in_array($routeName, ['tracker-edit'])) {
            return 'tracker-index';
        }  else if (in_array($routeName, ['workshop-type-edit'])) {
            return 'workshop-type-index';
        }  else if (in_array($routeName, ['supplier-edit'])) {
            return 'supplier-index';
        }  else if (in_array($routeName, ['schedule-edit'])) {
            return 'schedule-index';
        }  else if (in_array($routeName, ['fuel-limit-edit'])) {
            return 'fuel-limit-index';
        }  else if (in_array($routeName, ['fuel-limit-addition-edit'])) {
            return 'fuel-limit-addition-index';
        }  else if (in_array($routeName, ['fuel-bulk-entry-edit'])) {
            return 'fuel-bulk-entry-index';
        } else if (in_array($routeName, ['report-generator.report-generators-index'])) {
            return 'report-generator.report-generators-index';
        } else {
            return [
                [
                    'submenu_name' => $routeName,
                ]
            ];
        }
    }

    public static function activeMenus($routeName)
    {
        //$menus = [];
        try {
            $authorizationManager = new AuthorizationManager();
            $menus[] = $getRouteMenuId = $authorizationManager->findSubMenuId(self::getActiveRouteNameWrapping($routeName));

            if ($getRouteMenuId && !empty($getRouteMenuId)) {
                $bm = $authorizationManager->findParentMenu($getRouteMenuId);
                $menus[] = $bm['parent_submenu_id'];
                if ($bm && isset($bm['parent_submenu_id']) && !empty($bm['parent_submenu_id'])) {
                    $m = $authorizationManager->findParentMenu($bm['parent_submenu_id']);
                    if (!empty($m['submenu_id'])) {
                        $menus[] = $m['submenu_id'];
                    }
                }
            }
        } catch (\Exception $e) {
            $menus = [];
        }
        return is_array($menus) ? $menus : false;
    }

    public static function hasChildMenu($routeName)
    {
        $authorizationManager = new AuthorizationManager();
        $getRouteMenuId = $authorizationManager->findSubMenuId($routeName);
        return $authorizationManager->hasChildMenu($getRouteMenuId);
    }
}
