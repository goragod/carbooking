<?php

/**
 * @filesource modules/car/views/detail.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Detail;

use Kotchasan\Date;
use Kotchasan\Language;

/**
 * module=car-detail
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * แสดงรายละเอียด ยานพาหนะ
     *
     * @param object $index
     *
     * @return string
     */
    public function vehicle($index)
    {
        $content = '<article class=modal_detail>';
        $content .= '<header><h3 class=icon-shipping>{LNG_Details of} {LNG_Vehicle}</h3></header>';
        if (is_file(ROOT_PATH . DATA_FOLDER . 'car/' . $index->id . '.jpg')) {
            $content .= '<figure class="center"><img src="' . WEB_URL . DATA_FOLDER . 'car/' . $index->id . '.jpg"></figure>';
        }
        $content .= '<table class="border data fullwidth"><tbody>';
        $content .= '<tr><th>{LNG_Vehicle number}</th><td><span class="term" style="background-color:' . $index->color . '">' . $index->number . '</span></td></tr>';
        $category = \Car\Category\Model::init();
        foreach (Language::get('CAR_SELECT', array()) as $key => $label) {
            if (isset($index->{$key})) {
                $content .= '<tr><th>' . $label . '</th><td>' . $category->get($key, $index->{$key}) . '</td></tr>';
            }
        }
        $content .= '<tr><th>{LNG_Detail}</th><td>' . nl2br($index->detail) . '</td></tr>';
        $content .= '</tbody></article>';
        $content .= '</article>';
        // คืนค่า HTML
        return Language::trans($content);
    }

    /**
     * แสดงรายละเอียดการจอง
     *
     * @param object $index
     *
     * @return string
     */
    public function bookDetail($index)
    {
        $category = \Car\Category\Model::init();
        $content = '<article class=modal_detail>';
        $content .= '<header><h3 class=icon-shipping>{LNG_Details of} {LNG_Booking}</h3></header>';
        $content .= '<table class="border data fullwidth"><tbody>';
        $content .= '<tr><th>{LNG_Vehicle usage details}</th><td>' . $index->detail . '</td></tr>';
        $content .= '<tr><th>{LNG_Number of travelers}</th><td>' . $index->travelers . ' {LNG_persons}</td></tr>';
        $content .= '<tr><th>{LNG_Contact name}</th><td>' . $index->contact;
        if ($index->phone != '') {
            $content .= ' <a href="tel:' . $index->phone . '"><span class="icon-phone">' . $index->phone . '</span></a>';
        }
        $content .= '</td></tr>';
        $content .= '<tr><th>{LNG_Vehicle number}</th><td><span class="term" style="background-color:' . $index->color . '">' . $index->number . '</span></td></tr>';
        foreach (Language::get('CAR_SELECT', array()) as $key => $label) {
            if (isset($index->{$key})) {
                $content .= '<tr><th>' . $label . '</th><td>' . $category->get($key, $index->{$key}) . '</td></tr>';
            }
        }
        $chauffeur = array(-1 => '{LNG_Self drive}', 0 => '{LNG_Not specified (anyone)}', $index->chauffeur => $index->chauffeur_name);
        $content .= '<tr><th>{LNG_Chauffeur}</th><td>' . $chauffeur[$index->chauffeur];
        if ($index->chauffeur_phone != '') {
            $content .= ' <a href="tel:' . $index->chauffeur_phone . '"><span class="icon-phone">' . $index->chauffeur_phone . '</span></a>';
        }
        $content .= '</td></tr>';
        $content .= '<tr><th>{LNG_Date}</th><td>';
        list($begin_date, $begin_time) = explode(' ', $index->begin);
        list($end_date, $end_time) = explode(' ', $index->end);
        $content .= Date::format($begin_date, 'd M Y');
        if ($begin_date != $end_date) {
            $content .= '-' . Date::format($end_date, 'd M Y');
        }
        $content .= ' {LNG_time} ' . Date::format($begin_time, 'H:i') . '-' . Date::format($end_time, 'H:i');
        $content .= '</td></tr>';
        foreach (Language::get('CAR_OPTIONS', array()) as $key => $label) {
            if (!empty($index->{$key})) {
                $options = explode(',', $index->{$key});
                $vals = array();
                foreach ($category->toSelect($key) as $i => $v) {
                    if (in_array($i, $options)) {
                        $vals[] = $v;
                    }
                }
                $content .= '<tr><th>' . $label . '</th><td>' . implode(', ', $vals) . '</td></tr>';
            }
        }
        $content .= '<tr><th>{LNG_Status}</th><td><span class="term' . $index->status . '">' . Language::find('CAR_BOOKING_STATUS', null, $index->status) . '</span></td></tr>';
        $content .= '<tr><th>{LNG_Reason}</th><td>' . $index->reason . '</td></tr>';
        if ($index->comment != '') {
            $content .= '<tr><th>{LNG_Other}</th><td>' . nl2br($index->comment) . '</td></tr>';
        }
        $content .= '</tbody></article>';
        $content .= '</article>';
        // คืนค่า HTML
        return Language::trans($content);
    }
}
