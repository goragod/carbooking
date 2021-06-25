<?php

/**
 * @filesource modules/car/views/order.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Order;

use Kotchasan\Html;
use Kotchasan\Language;

/**
 * module=car-order
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * ฟอร์มแก้ไข การจอง
     *
     * @param object $index
     *
     * @return string
     */
    public function render($index)
    {
        $form = Html::create('form', array(
            'id' => 'setup_frm',
            'class' => 'setup_frm',
            'autocomplete' => 'off',
            'action' => 'index.php/car/model/order/submit',
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
            'value' => $index->vehicle_id,
        ));
        // detail
        $fieldset->add('textarea', array(
            'id' => 'detail',
            'labelClass' => 'g-input icon-file',
            'itemClass' => 'item',
            'label' => '{LNG_Vehicle usage details}',
            'rows' => 3,
            'value' => $index->detail,
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
            'value' => $index->name,
        ));
        // phone
        $groups->add('text', array(
            'id' => 'phone',
            'labelClass' => 'g-input icon-phone',
            'itemClass' => 'width50',
            'label' => '{LNG_Phone}',
            'disabled' => true,
            'value' => $index->phone,
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
        foreach (Language::get('CAR_OPTIONS') as $key => $label) {
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
            'value' => $index->comment,
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
        // status
        $fieldset->add('select', array(
            'id' => 'status',
            'labelClass' => 'g-input icon-star0',
            'itemClass' => 'item',
            'label' => '{LNG_Status}',
            'options' => Language::get('CAR_BOOKING_STATUS'),
            'value' => $index->status,
        ));
        // reason
        $fieldset->add('text', array(
            'id' => 'reason',
            'labelClass' => 'g-input icon-question',
            'itemClass' => 'item',
            'label' => '{LNG_Reason}',
            'maxlength' => 128,
            'value' => $index->reason,
        ));
        $fieldset = $form->add('fieldset', array(
            'class' => 'submit',
        ));
        // submit
        $fieldset->add('submit', array(
            'class' => 'button ok large icon-save',
            'value' => '{LNG_Save}',
        ));
        if (self::$cfg->noreply_email != '') {
            $fieldset->add('checkbox', array(
                'id' => 'send_mail',
                'label' => '&nbsp;{LNG_Email the relevant person}',
                'value' => 1,
            ));
        }
        // id
        $fieldset->add('hidden', array(
            'id' => 'id',
            'value' => $index->id,
        ));
        // Javascript
        $form->script('initCalendarRange("begin_date", "end_date");');
        // คืนค่า HTML
        return $form->render();
    }
}
