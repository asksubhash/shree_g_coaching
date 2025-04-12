<?php

namespace App\Helpers;

use Auth;
use App\Models\Menu;
use App\Models\SubMenu;

class MenuHelper
{
    public static function getMenus()
    {
        $query = Menu::select('menus.*', 'resources.resource_link');
        $query->leftJoin('resources', 'resources.id', '=', 'menus.resource_id');
        $data = $query->where([
            'menus.record_status' => 1,
            'menus.role_code' => Auth::user()->role_code
        ])->orderby('menus.sl_no', 'ASC')->get();

        return $data;
    }

    public static function getSubMenus()
    {
        $query = SubMenu::select('sub_menus.*', 'resources.resource_link');
        $query->leftJoin('menus', 'menus.id', '=', 'sub_menus.menu_id');
        $query->leftJoin('resources', 'resources.id', '=', 'sub_menus.resource_id');
        $data = $query->where([
            'sub_menus.record_status' => 1,
            'menus.role_code' => Auth::user()->role_code
        ])->orderBy('sub_menus.sl_no', 'ASC')->get();

        return $data;
    }

    public static function setMenuOpen($sub_menus)
    {

        // Get the current path
        $uri = request()->path();
        // Get all the sub menus links
        $resource_links = array_column($sub_menus, 'resource_link');

        // Set the opened class if available in resources links array
        $opened_class = (in_array($uri, $resource_links)) ? 'menu-is-opening menu-open' : '';
        return $opened_class;
    }

    public static function setMenuOpenWithArrayLinks($resource_links)
    {

        // Get the current path
        $uri = request()->path();

        // Set the opened class if available in resources links array
        $opened_class = (in_array($uri, $resource_links)) ? 'menu-is-opening menu-open' : '';
        return $opened_class;
    }

    public static function setMenuActive($resource_link)
    {

        // Get the current path
        $uri = request()->path();

        // Set the opened class if available in resources links array
        $opened_class = ($uri  == $resource_link) ? 'active' : '';
        return $opened_class;
    }
}
