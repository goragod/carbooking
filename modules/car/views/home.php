<?php

/**
 * @filesource modules/car/views/home.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Home;

use Kotchasan\Html;

/**
 * หน้า Home
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * หน้า Home
     *
     * @param object $index
     * @param array  $login
     *
     * @return string
     */
    public function render($index, $login)
    {
        $section = Html::create('section');
        $section->add('h3', array(
            'innerHTML' => '<span class="icon-shipping">{LNG_Booking calendar} {LNG_Vehicle}</span>',
        ));
        $div = $section->add('div', array(
            'class' => 'setup_frm',
        ));
        $div->add('div', array(
            'id' => 'car-calendar',
            'class' => 'margin-left-right-bottom-top',
        ));
        // สีของห้องทั้งหมด
        $query = \Car\Vehicles\Model::toDataTable()->cacheOn();
        $cars = '';
        foreach ($query->execute() as $item) {
            $cars .= '<a id=car_' . $item->id . ' style="background-color:' . $item->color . '">' . $item->number . '</a>';
        }
        $div->add('div', array(
            'id' => 'car_links',
            'class' => 'calendar_links clear',
            'innerHTML' => $cars,
        ));
        /* Javascript */
        $section->script('initCarCalendar();');
        // คืนค่า HTML
        return $section->render();
    }
}
