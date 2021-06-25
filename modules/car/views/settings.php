<?php

/**
 * @filesource modules/car/views/settings.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Settings;

use Kotchasan\Html;
use Kotchasan\Language;

/**
 * module=car-settings
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * ฟอร์มตั้งค่า person.
     *
     * @return string
     */
    public function render()
    {
        $form = Html::create('form', array(
            'id' => 'setup_frm',
            'class' => 'setup_frm',
            'autocomplete' => 'off',
            'action' => 'index.php/car/model/settings/submit',
            'onsubmit' => 'doFormSubmit',
            'ajax' => true,
            'token' => true,
        ));
        $fieldset = $form->add('fieldset', array(
            'title' => '{LNG_Module settings}',
        ));
        // chauffeur_stats
        $fieldset->add('select', array(
            'id' => 'chauffeur_stats',
            'labelClass' => 'g-input icon-star0',
            'itemClass' => 'item',
            'label' => '{LNG_Chauffeur}',
            'comment' => '{LNG_Status of members who are drivers}',
            'options' => self::$cfg->member_status,
            'value' => isset(self::$cfg->chauffeur_stats) ? self::$cfg->chauffeur_stats : 2,
        ));
        // car_approving
        $fieldset->add('select', array(
            'id' => 'car_approving',
            'labelClass' => 'g-input icon-write',
            'itemClass' => 'item',
            'label' => '{LNG_Approving/editing reservations}',
            'options' => Language::get('APPROVING_RESERVATIONS'),
            'value' => isset(self::$cfg->car_approving) ? self::$cfg->car_approving : 0,
        ));
        $fieldset = $form->add('fieldset', array(
            'title' => '{LNG_Size of} {LNG_Image}',
        ));
        // car_w
        $fieldset->add('text', array(
            'id' => 'car_w',
            'labelClass' => 'g-input icon-width',
            'itemClass' => 'item',
            'label' => '{LNG_Width}',
            'comment' => '{LNG_Image size is in pixels} ({LNG_resized automatically})',
            'value' => isset(self::$cfg->car_w) ? self::$cfg->car_w : 600,
        ));
        $fieldset = $form->add('fieldset', array(
            'class' => 'submit',
        ));
        // submit
        $fieldset->add('submit', array(
            'class' => 'button save large icon-save',
            'value' => '{LNG_Save}',
        ));
        // คืนค่า HTML
        return $form->render();
    }
}
