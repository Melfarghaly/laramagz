<?php

namespace App\Helpers;

use Harimayco\Menu\Facades\Menu;

class Appearance
{
    /**
     * @return mixed
     */
    public static function getMenu($menu)
    {
        return Menu::getByName($menu);
    }
    
    /**
     * @return mixed
     */
    public static function getMenuHeader()
    {
        return Menu::getByName('header');
    }

    /**
     * @return mixed
     */
    public static function getMenuFooter()
    {
        return Menu::getByName('footer');
    }

    /**
     * @param $menuId
     * @param $menuList
     * @return string
     */
    public static function getBuildMenu($menuId, $menuList)
    {
        $html ='<ul class="nav-list">';
        foreach($menuList[$menuId] as $menu) {

        $class = $menu['child'] ? 'dropdown magz-dropdown' : '';
        $icon = $menu['child'] ? '<i class="ion-ios-arrow-right"></i>' : '';

        $html .="<li class=\"".$class."\"><a href=\"".$menu['link']."\">".$menu['label'] ." ".$icon."</a>";
        if($menu['child']) {


        $html .='<ul class="dropdown-menu">';
        foreach($menu['child'] as $child) {
        $html .='<li><a href="'.$child['link'].'">'.$child['label'].'</a></li>';
        }
        $html .='</ul>';


        }
        $html .='</li>';
        }
        $html .='</ul>';

        return $html;
    }

    /**
     * @param $menu
     * @return string
     */
    public static function getChild($menu)
    {
        $html = '<ul class="dropdown-menu">';
        foreach( $menu as $child ) {

            $class = $child['child'] ? 'dropdown magz-dropdown' : '';
            $icon = ($child['child']) ? '<i class="ion-ios-arrow-right"></i>' : '';
            $html .= '<li class="'.$class.'">';
            $html .= '<a href="'.$child['link'].'" title="">'.$child['label'] .' '.$icon.'</a>';
            // dd($child);
            if($child['child']) {
            echo 'yes';
                self::getChild($child['child']);
            }

            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }
}

