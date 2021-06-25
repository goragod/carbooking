<?php

/**
 * @filesource modules/car/views/vehicles.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Vehicles;

use Kotchasan\DataTable;
use Kotchasan\Http\Request;
use Kotchasan\Language;
use Kotchasan\Text;

/**
 * module=car-vehicles
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
    private $car_select;
    /**
     * @var object
     */
    private $category;

    /**
     * ตารางยานพาหนะ
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        $this->category = \Car\Category\Model::init();
        $this->car_select = Language::get('CAR_SELECT', array());
        $headers = array(
            'id' => array(
                'text' => '{LNG_Image}',
                'class' => 'center',
            ),
            'number' => array(
                'text' => '{LNG_Vehicle number}',
                'sort' => 'number',
            ),
        );
        $cols = array(
            'id' => array(
                'class' => 'center',
            ),
        );
        $filters = array();
        foreach ($this->car_select as $type => $text) {
            $filters[$type] = array(
                'name' => $type,
                'default' => 0,
                'text' => $text,
                'options' => array(0 => '{LNG_all items}') + $this->category->toSelect($type),
                'value' => $request->request($type)->toInt(),
            );
            $headers[$type] = array(
                'text' => $text,
                'class' => 'center',
                'sort' => $text,
            );
            $cols[$type] = array('class' => 'center');
        }
        $headers['seats'] = array(
            'text' => '{LNG_Number of seats}',
            'class' => 'center',
            'sort' => 'seats',
        );
        $cols['seats'] = array('class' => 'center');
        // URL สำหรับส่งให้ตาราง
        $uri = $request->createUriWithGlobals(WEB_URL . 'index.php');
        // ตาราง
        $table = new DataTable(array(
            /* Uri */
            'uri' => $uri,
            /* Model */
            'model' => \Car\Vehicles\Model::toDataTable(),
            /* รายการต่อหน้า */
            'perPage' => $request->cookie('car_perPage', 30)->toInt(),
            /* ฟังก์ชั่นจัดรูปแบบการแสดงผลแถวของตาราง */
            'onRow' => array($this, 'onRow'),
            /* คอลัมน์ที่ไม่ต้องแสดงผล */
            'hideColumns' => array('detail', 'color'),
            /* ตั้งค่าการกระทำของของตัวเลือกต่างๆ ด้านล่างตาราง ซึ่งจะใช้ร่วมกับการขีดถูกเลือกแถว */
            'action' => 'index.php/car/model/vehicles/action',
            'actionCallback' => 'dataTableActionCallback',
            /* ตัวเลือกด้านบนของตาราง ใช้จำกัดผลลัพท์การ query */
            'filters' => $filters,
            /* ส่วนหัวของตาราง และการเรียงลำดับ (thead) */
            'headers' => $headers,
            /* รูปแบบการแสดงผลของคอลัมน์ (tbody) */
            'cols' => $cols,
            /* ปุ่มแสดงในแต่ละแถว */
            'buttons' => array(
                'car' => array(
                    'class' => 'icon-addtocart button blue',
                    'id' => ':id',
                    'text' => '{LNG_Book a vehicle}',
                ),
                'detail' => array(
                    'class' => 'icon-info button orange',
                    'id' => ':id',
                    'text' => '{LNG_Detail}',
                ),
            ),
        ));
        // save cookie
        setcookie('car_perPage', $table->perPage, time() + 2592000, '/', HOST, HTTPS, true);
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
        $item['number'] = '<span class="term" style="background-color:' . $item['color'] . '">' . $item['number'] . '</span><p>' . Text::cut(strip_tags($item['detail']), 150) . '</p>';
        $thumb = is_file(ROOT_PATH . DATA_FOLDER . 'car/' . $item['id'] . '.jpg') ? WEB_URL . DATA_FOLDER . 'car/' . $item['id'] . '.jpg' : WEB_URL . 'modules/car/img/noimage.png';
        $item['id'] = '<img src="' . $thumb . '" style="max-height:100px;max-width:100px" alt=thumbnail>';
        foreach ($this->car_select as $type => $text) {
            $item[$type] = $this->category->get($type, $item[$type]);
        }
        return $item;
    }
}
