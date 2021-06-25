<?php

/**
 * @filesource modules/car/controllers/vehicles.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Vehicles;

use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=car-vehicles
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * ตารางรายการ รถยนต์
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        // ข้อความ title bar
        $this->title = Language::trans('{LNG_List of} {LNG_Vehicle}');
        // เลือกเมนู
        $this->menu = 'vehicles';
        // แสดงผล
        $section = Html::create('section', array(
            'class' => 'content_bg',
        ));
        // breadcrumbs
        $breadcrumbs = $section->add('div', array(
            'class' => 'breadcrumbs',
        ));
        $ul = $breadcrumbs->add('ul');
        $ul->appendChild('<li><span class="icon-home">{LNG_Vehicle}</span></li>');
        $ul->appendChild('<li><span>{LNG_List of}</span></li>');
        $section->add('header', array(
            'innerHTML' => '<h2 class="icon-shipping">' . $this->title . '</h2>',
        ));
        // แสดงตาราง
        $section->appendChild(\Car\Vehicles\View::create()->render($request));
        // คืนค่า HTML
        return $section->render();
    }
}
