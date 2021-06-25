<?php

/**
 * @filesource modules/car/views/index.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Index;

use Kotchasan\DataTable;
use Kotchasan\Date;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=car
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * @var array
     */
    private $status;

    /**
     * รายการจองของฉัน
     *
     * @param Request $request
     * @param array   $login
     *
     * @return string
     */
    public function render(Request $request, $login)
    {
        $this->status = Language::get('CAR_BOOKING_STATUS');
        // URL สำหรับส่งให้ตาราง
        $uri = $request->createUriWithGlobals(WEB_URL . 'index.php');
        // ตาราง
        $table = new DataTable(array(
            /* Uri */
            'uri' => $uri,
            /* Model */
            'model' => \Car\Index\Model::toDataTable($login['id']),
            /* รายการต่อหน้า */
            'perPage' => $request->cookie('carIndex_perPage', 30)->toInt(),
            /* เรียงลำดับ */
            'sort' => 'begin DESC',
            /* ฟังก์ชั่นจัดรูปแบบการแสดงผลแถวของตาราง */
            'onRow' => array($this, 'onRow'),
            /* คอลัมน์ที่ไม่ต้องแสดงผล */
            'hideColumns' => array('id', 'today', 'color'),
            /* คอลัมน์ที่สามารถค้นหาได้ */
            'searchColumns' => array('detail', 'number'),
            /* ตั้งค่าการกระทำของของตัวเลือกต่างๆ ด้านล่างตาราง ซึ่งจะใช้ร่วมกับการขีดถูกเลือกแถว */
            'action' => 'index.php/car/model/index/action',
            'actionCallback' => 'dataTableActionCallback',
            /* ตัวเลือกด้านบนของตาราง ใช้จำกัดผลลัพท์การ query */
            'filters' => array(
                'status' => array(
                    'name' => 'status',
                    'default' => -1,
                    'text' => '{LNG_Status}',
                    'options' => array(-1 => '{LNG_all items}') + Language::get('CAR_BOOKING_STATUS'),
                    'value' => $request->request('status', -1)->toInt(),
                ),
            ),
            /* ส่วนหัวของตาราง และการเรียงลำดับ (thead) */
            'headers' => array(
                'detail' => array(
                    'text' => '{LNG_Vehicle usage details}',
                ),
                'vehicle_id' => array(
                    'text' => '{LNG_Image}',
                    'class' => 'center',
                ),
                'number' => array(
                    'text' => '{LNG_Vehicle}',
                ),
                'begin' => array(
                    'text' => '{LNG_Date}',
                    'class' => 'center',
                ),
                'end' => array(
                    'text' => '{LNG_time}',
                    'class' => 'center',
                ),
                'status' => array(
                    'text' => '{LNG_Status}',
                    'class' => 'center',
                ),
                'reason' => array(
                    'text' => '{LNG_Reason}',
                ),
            ),
            /* รูปแบบการแสดงผลของคอลัมน์ (tbody) */
            'cols' => array(
                'vehicle_id' => array(
                    'class' => 'center',
                ),
                'begin' => array(
                    'class' => 'center',
                ),
                'end' => array(
                    'class' => 'center',
                ),
                'status' => array(
                    'class' => 'center',
                ),
            ),
            /* ฟังก์ชั่นตรวจสอบการแสดงผลปุ่มในแถว */
            'onCreateButton' => array($this, 'onCreateButton'),
            /* ปุ่มแสดงในแต่ละแถว */
            'buttons' => array(
                'cancel' => array(
                    'class' => 'icon-warning button cancel',
                    'id' => ':id',
                    'text' => '{LNG_Cancel}',
                ),
                'edit' => array(
                    'class' => 'icon-edit button green',
                    'href' => $uri->createBackUri(array('module' => 'car-booking', 'id' => ':id')),
                    'text' => '{LNG_Edit}',
                ),
                'detail' => array(
                    'class' => 'icon-info button orange',
                    'id' => ':id',
                    'text' => '{LNG_Detail}',
                ),
            ),
            /* ปุ่มเพิ่ม */
            'addNew' => array(
                'class' => 'float_button icon-addtocart',
                'href' => 'index.php?module=car-booking',
                'title' => '{LNG_Book a vehicle}',
            ),
        ));
        // save cookie
        setcookie('carIndex_perPage', $table->perPage, time() + 2592000, '/', HOST, HTTPS, true);
        // คืนค่า HTML
        return $table->render();
    }

    /**
     * จัดรูปแบบการแสดงผลในแต่ละแถว.
     *
     * @param array  $item ข้อมูลแถว
     * @param int    $o    ID ของข้อมูล
     * @param object $prop กำหนด properties ของ TR
     *
     * @return array
     */
    public function onRow($item, $o, $prop)
    {
        if ($item['today'] == 1) {
            $prop->class = 'bg3';
        }
        $thumb = is_file(ROOT_PATH . DATA_FOLDER . 'car/' . $item['vehicle_id'] . '.jpg') ? WEB_URL . DATA_FOLDER . 'car/' . $item['vehicle_id'] . '.jpg' : WEB_URL . 'modules/car/img/noimage.png';
        $item['vehicle_id'] = '<img src="' . $thumb . '" style="max-height:50px;max-width:50px" alt=thumbnail>';
        $item['status'] = '<span class="term' . $item['status'] . '">' . $this->status[$item['status']] . '</span>';
        list($begin_date, $begin_time) = explode(' ', $item['begin']);
        list($end_date, $end_time) = explode(' ', $item['end']);
        $item['begin'] = Date::format($begin_date, 'd M Y');
        if ($begin_date != $end_date) {
            $item['begin'] .= '-' . Date::format($end_date, 'd M Y');
        }
        $item['end'] = Date::format($begin_time, 'H:i') . '-' . Date::format($end_time, 'H:i');
        $item['number'] = '<span class="term" style="background-color:' . $item['color'] . '">' . $item['number'] . '</span>';
        return $item;
    }

    /**
     * ฟังกชั่นตรวจสอบว่าสามารถสร้างปุ่มได้หรือไม่.
     *
     * @param array $item
     *
     * @return array
     */
    public function onCreateButton($btn, $attributes, $items)
    {
        if ($btn == 'edit') {
            return $items['status'] == 0 && $items['today'] == 0 ? $attributes : false;
        } elseif ($btn == 'cancel') {
            return $items['status'] == 0 ? $attributes : false;
        } else {
            return $attributes;
        }
    }
}
