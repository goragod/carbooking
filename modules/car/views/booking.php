<?php

/**
 * @filesource modules/car/views/booking.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Booking;

use Kotchasan\Html;
use Kotchasan\Language;

/**
 * module=car-booking
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * ฟอร์มสร้าง/แก้ไข การจอง
     *
     * @param object $index
     * @param array  $login
     *
     * @return string
     */
    public function render($index, $login)
    {
        $form = Html::create('form', array(
            'id' => 'setup_frm',
            'class' => 'setup_frm',
            'autocomplete' => 'off',
            'action' => 'index.php/car/model/booking/submit',
            'onsubmit' => 'doFormSubmit',
            'ajax' => true,
            'token' => true,
        ));
        $fieldset = $form->add('fieldset', array(
            'title' => '{LNG_Details of} {LNG_Vehicle}',
        ));
        // vehicle_id
        $fieldset->add('select', array(
            'id' => 'vehicle_id',
            'labelClass' => 'g-input icon-shipping',
            'itemClass' => 'item',
            'label' => '{LNG_Vehicle}',
            'options' => \Car\Vehicles\Model::toSelect(),
            'value' => isset($index->vehicle_id) ? $index->vehicle_id : 0,
        ));
        // detail
        $fieldset->add('textarea', array(
            'id' => 'detail',
            'labelClass' => 'g-input icon-file',
            'itemClass' => 'item',
            'label' => '{LNG_Vehicle usage details}',
            'rows' => 3,
            'value' => isset($index->detail) ? $index->detail : '',
        ));
        // travelers
        $fieldset->add('number', array(
            'id' => 'travelers',
            'labelClass' => 'g-input icon-number',
            'itemClass' => 'item',
            'label' => '{LNG_Number of travelers}',
            'unit' => '{LNG_persons}',
            'value' => isset($index->travelers) ? $index->travelers : 1,
        ));
        $groups = $fieldset->add('groups');
        // name
        $groups->add('text', array(
            'id' => 'name',
            'labelClass' => 'g-input icon-customer',
            'itemClass' => 'width50',
            'label' => '{LNG_Contact name}',
            'disabled' => true,
            'value' => $index->id == 0 ? $login['name'] : $index->name,
        ));
        // phone
        $groups->add('text', array(
            'id' => 'phone',
            'labelClass' => 'g-input icon-phone',
            'itemClass' => 'width50',
            'label' => '{LNG_Phone}',
            'maxlength' => 32,
            'value' => $index->id == 0 ? $login['phone'] : $index->phone,
        ));
        // begin
        if (empty($index->begin)) {
            $begin_date = null;
            $begin_time = null;
        } else {
            $time = strtotime($index->begin);
            $begin_date = date('Y-m-d', $time);
            $begin_time = date('H:i', $time);
        }
        $groups = $fieldset->add('groups', array(
            'for' => 'begin_date',
            'label' => '{LNG_Begin date}/{LNG_Begin time}',
        ));
        // begin date
        $groups->add('date', array(
            'id' => 'begin_date',
            'labelClass' => 'g-input icon-calendar',
            'itemClass' => 'width50',
            'title' => '{LNG_Begin date}',
            'min' => date('Y-m-d'),
            'value' => $begin_date,
        ));
        // begin time
        $groups->add('time', array(
            'id' => 'begin_time',
            'labelClass' => 'g-input icon-clock',
            'itemClass' => 'width50',
            'title' => '{LNG_Begin time}',
            'value' => $begin_time,
        ));
        // end
        if (empty($index->end)) {
            $end_date = null;
            $end_time = null;
        } else {
            $time = strtotime($index->end);
            $end_date = date('Y-m-d', $time);
            $end_time = date('H:i', $time);
        }
        $groups = $fieldset->add('groups', array(
            'for' => 'end_date',
            'label' => '{LNG_End date}/{LNG_End time}',
        ));
        // end date
        $groups->add('date', array(
            'id' => 'end_date',
            'labelClass' => 'g-input icon-calendar',
            'itemClass' => 'width50',
            'title' => '{LNG_End date}',
            'min' => date('Y-m-d'),
            'value' => $end_date,
        ));
        // end time
        $groups->add('time', array(
            'id' => 'end_time',
            'labelClass' => 'g-input icon-clock',
            'itemClass' => 'width50',
            'title' => '{LNG_End time}',
            'value' => $end_time,
        ));
        // ตัวเลือก checkbox
        $category = \Car\Category\Model::init();
        foreach (Language::get('CAR_OPTIONS', array()) as $key => $label) {
            $fieldset->add('checkboxgroups', array(
                'id' => $key,
                'labelClass' => 'g-input icon-category',
                'itemClass' => 'item',
                'label' => $label,
                'options' => $category->toSelect($key),
                'value' => isset($index->{$key}) ? explode(',', $index->{$key}) : array(),
            ));
        }
        // comment
        $fieldset->add('textarea', array(
            'id' => 'comment',
            'labelClass' => 'g-input icon-file',
            'itemClass' => 'item',
            'label' => '{LNG_Other}',
            'rows' => 3,
            'value' => isset($index->comment) ? $index->comment : '',
        ));
        // chauffeur
        $fieldset->add('select', array(
            'id' => 'chauffeur',
            'labelClass' => 'g-input icon-customer',
            'itemClass' => 'item',
            'label' => '{LNG_Chauffeur}',
            'options' => array(-1 => '{LNG_Do not want}', 0 => '{LNG_Not specified (anyone)}') + \Car\Chauffeur\Model::init()->toSelect(),
            'value' => isset($index->chauffeur) ? $index->chauffeur : 0,
        ));
        $fieldset = $form->add('fieldset', array(
            'class' => 'submit',
        ));
        // submit
        $fieldset->add('submit', array(
            'class' => 'button ok large icon-save',
            'value' => '{LNG_Save}',
        ));
        // id
        $fieldset->add('hidden', array(
            'id' => 'id',
            'value' => $index->id,
        ));
        // member_id
        $fieldset->add('hidden', array(
            'id' => 'member_id',
            'value' => $index->id == 0 ? $login['id'] : $index->member_id,
        ));
        // Javascript
        $form->script('initCalendarRange("begin_date", "end_date");');
        // คืนค่า HTML
        return $form->render();
    }
}
