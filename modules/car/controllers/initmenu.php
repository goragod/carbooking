<?php

/**
 * @filesource modules/car/controllers/initmenu.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Initmenu;

use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * Init Menu
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\KBase
{
    /**
     * ฟังก์ชั่นเริ่มต้นการทำงานของโมดูลที่ติดตั้ง
     * และจัดการเมนูของโมดูล
     *
     * @param Request                $request
     * @param \Index\Menu\Controller $menu
     * @param array                  $login
     */
    public static function execute(Request $request, $menu, $login)
    {
        $menu->addTopLvlMenu('vehicles', '{LNG_Book a vehicle}', 'index.php?module=car-vehicles', null, 'module');
        if ($login) {
            $menu->addTopLvlMenu('booking', '{LNG_My Booking}', null, null, 'module');
            $menu->add('booking', '{LNG_Vehicle}', 'index.php?module=car', null, 'vehicle');
        }
        if (Login::checkPermission($login, 'can_manage_car')) {
            // เมนูตั้งค่า
            $submenus = array(
                array(
                    'text' => '{LNG_Settings}',
                    'url' => 'index.php?module=car-settings',
                ),
                array(
                    'text' => '{LNG_List of} {LNG_Vehicle}',
                    'url' => 'index.php?module=car-setup',
                ),
            );
            foreach (Language::get('CAR_OPTIONS', array()) as $type => $text) {
                $submenus[] = array(
                    'text' => $text,
                    'url' => 'index.php?module=car-categories&amp;type=' . $type,
                );
            }
            foreach (Language::get('CAR_SELECT', array()) as $type => $text) {
                $submenus[] = array(
                    'text' => $text,
                    'url' => 'index.php?module=car-categories&amp;type=' . $type,
                );
            }
            $menu->add('settings', '{LNG_Vehicle}', null, $submenus, 'car');
        }
        if (Login::checkPermission($login, 'can_approve_car')) {
            $menu->add('report', '{LNG_Book a vehicle}', 'index.php?module=car-report', null, 'vehicle');
        }
    }
}
