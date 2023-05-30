<?php
namespace App\Helpers;

use App\Entities\Security\Menu;
use App\Entities\VSL\JettyList;
use App\Enums\ModuleInfo;
use App\Managers\Authorization\AuthorizationManager;
use Illuminate\Support\Facades\Auth;

class MdaClass
{

    public $id;
    public $links;

    /**
     * @return mixed
     */
    public static function menuSetup()
    {
        if (Auth::user()->hasGrantAll()) {
            $moduleId = ModuleInfo::MDA_MODULE_ID;
            $menus = Menu::where('module_id', $moduleId)->orderBy('menu_order_no')->get();

            return $menus;
        } else {
            $allMenus = Auth::user()->getRoleMenus();
            $menus = [];

            if($allMenus) {
                foreach($allMenus as $menu) {
                    if($menu->module_id == ModuleInfo::MDA_MODULE_ID) {
                        $menus[] = $menu;
                    }
                }
            }

            return $menus;
        }
    }

    public static function getActiveRouteNameWrapping($routeName)
    {
//        dd($routeName);
//         return $routeName = ($getRouteMenuId)?$routeName:[  [ 'submenu_name' => $routeName,  ] ];

        if (in_array($routeName, ['mwe.operation.maintenance-req-auth-by-xen'])) {
            return 'mwe.operation.maintenance-request';
        }elseif (in_array($routeName, ['mwe.operation.request-inspection-report'])) {
            return 'mwe.operation.request-inspection';
        } elseif (in_array($routeName, ['mwe.operation.workshop-requisition-create'])) {
            return 'mwe.operation.workshop-requisition';
        } elseif (in_array($routeName, ['mwe.operation.workshop-auth-requisition-create'])) {
            return 'mwe.operation.workshop-requisition-auth';
        } elseif (in_array($routeName, ['mwe.operation.third-party-assign'])) {
            return 'mwe.operation.workshop-requisition';
        } elseif (in_array($routeName, ['mwe.operation.third-party-req-assign'])) {
            return 'mwe.operation.third-party-requests';
        } elseif (in_array($routeName, ['mwe.operation.third-party-tasks-monitor'])) {
            return 'mwe.operation.third-party-tasks-monitor-index';
        } elseif (in_array($routeName, ['mwe.setting.company-setup-edit'])) {
            return 'company-setup';
        } else if (in_array($routeName, ['cms.shifting.shift','cms.offday','cms.shifting.shift-edit','cms.offday.edit'])) {
            return 'cms.shifting.duties';
        }  else if (in_array($routeName, ['cms.vessel-engine-mapping','cms.vessel-engine-mapping.edit'])) {
            return 'cms.vessel';
        }  else if (in_array($routeName, ['cms.fuel-consumption','cms.fuel-consumption.edit'])) {
            return 'cms.user-wise-vessel';
        } else {
            $authorizationManager = new AuthorizationManager();
            $getRouteMenuId = $authorizationManager->findSubMenuId($routeName);
            if (!$getRouteMenuId) {
                $getRouteMenuId = $authorizationManager->findSubMenuId(substr($routeName, 0, -5));
                $routeName =substr($routeName, 0, -5);
            }
            $routeName = ($getRouteMenuId)?$routeName:[  [ 'submenu_name' => $routeName,  ] ];
        }

        return $routeName;
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

    public static function getJettyName($id)
    {
        return JettyList::where('jetty_id', $id)->where('status', '!=', 'D')->pluck('jetty_name')->first();
    }
}
