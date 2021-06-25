<?php

/**
 * @filesource modules/car/views/write.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Write;

use Kotchasan\Html;
use Kotchasan\Language;

/**
 * module=car-write.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * ฟอร์มสร้าง/แก้ไข เอกสาร.
     *
     * @param object $index
     * @param array  $login
     *
     * @return string
     */
    public function render($index, $login)
    {
        // form
        $form = Html::create('form', array(
            'id' => 'setup_frm',
            'class' => 'setup_frm',
            'autocomplete' => 'off',
            'action' => 'index.php/car/model/write/submit',
            'onsubmit' => 'doFormSubmit',
            'ajax' => true,
            'token' => true,
        ));
        $fieldset = $form->add('fieldset', array(
            'title' => '{LNG_Details of} {LNG_Vehicle}',
        ));
        // number
        $fieldset->add('text', array(
            'id' => 'number',
            'labelClass' => 'g-input icon-number',
            'itemClass' => 'item',
            'label' => '{LNG_Vehicle number}',
            'maxlength' => 20,
            'value' => isset($index->number) ? $index->number : '',
        ));
        // color
        $fieldset->add('color', array(
            'id' => 'color',
            'labelClass' => 'g-input icon-color',
            'itemClass' => 'item',
            'label' => '{LNG_Color}',
            'value' => isset($index->color) ? $index->color : '',
        ));
        $category = \Car\Category\Model::init();
        foreach (Language::get('CAR_SELECT') as $key => $label) {
            $fieldset->add('text', array(
                'id' => $key,
                'labelClass' => 'g-input icon-category',
                'itemClass' => 'item',
                'label' => $label,
                'datalist' => $category->toSelect($key),
                'nameValue' => '',
                'value' => isset($index->{$key}) ? $index->{$key} : '',
            ));
        }
        // seats
        $fieldset->add('number', array(
            'id' => 'seats',
            'labelClass' => 'g-input icon-number',
            'itemClass' => 'item',
            'label' => '{LNG_Number of seats}',
            'value' => isset($index->seats) ? $index->seats : '',
        ));
        // detail
        $fieldset->add('textarea', array(
            'id' => 'detail',
            'labelClass' => 'g-input icon-file',
            'itemClass' => 'item',
            'label' => '{LNG_Detail}',
            'rows' => 3,
            'value' => isset($index->detail) ? $index->detail : '',
        ));
        // picture
        if (is_file(ROOT_PATH . DATA_FOLDER . 'car/' . $index->id . '.jpg')) {
            $img = WEB_URL . DATA_FOLDER . 'car/' . $index->id . '.jpg?' . time();
        } else {
            $img = WEB_URL . 'modules/car/img/noimage.png';
        }
        $fieldset->add('file', array(
            'id' => 'picture',
            'labelClass' => 'g-input icon-upload',
            'itemClass' => 'item',
            'label' => '{LNG_Image}',
            'comment' => '{LNG_Browse image uploaded, type :type} ({LNG_resized automatically})',
            'dataPreview' => 'imgPicture',
            'previewSrc' => $img,
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
        \Gcms\Controller::$view->setContentsAfter(array(
            '/:type/' => 'jpg, jpeg, png',
        ));
        // คืนค่า HTML
        return $form->render();
    }
}
