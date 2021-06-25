<?php

/**
 * @filesource modules/car/controllers/write.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Write;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=car-write
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * เพิ่ม-แก้ไข รถยนต์
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        // ข้อความ title bar
        $this->title = Language::get('Vehicle');
        // เลือกเมนู
        $this->menu = 'settings';
        // สมาชิก
        $login = Login::isMember();
        // ตรวจสอบรายการที่เลือก
        $index = \Car\Write\Model::get($request->request('id')->toInt());
        // สามารถจัดการห้องประชุมได้
        if ($index && Login::checkPermission($login, 'can_manage_car')) {
            // ข้อความ title bar
            $title = Language::get($index->id == 0 ? 'Add New' : 'Edit');
            $this->title = $title . ' ' . $this->title;
            // แสดงผล
            $section = Html::create('section', array(
                'class' => 'content_bg',
            ));
            // breadcrumbs
            $breadcrumbs = $section->add('div', array(
                'class' => 'breadcrumbs',
            ));
            $ul = $breadcrumbs->add('ul');
            $ul->appendChild('<li><span class="icon-shipping">{LNG_Module}</span></li>');
            $ul->appendChild('<li><a href="{BACKURL?module=car-setup&id=0}">{LNG_Book a vehicle}</a></li>');
            $ul->appendChild('<li><span>' . $title . '</span></li>');
            $section->add('header', array(
                'innerHTML' => '<h2 class="icon-write">' . $this->title . '</h2>',
            ));
            // menu
            $section->appendChild(\Index\Tabmenus\View::render($request, 'settings', 'car'));
            // แสดงฟอร์ม
            $section->appendChild(\Car\Write\View::create()->render($index, $login));
            // คืนค่า HTML
            return $section->render();
        }
        // 404
        return \Index\Error\Controller::execute($this, $request->getUri());
    }
}
