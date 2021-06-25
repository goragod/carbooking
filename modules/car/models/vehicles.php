<?php

/**
 * @filesource modules/car/models/vehicles.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Vehicles;

use Gcms\Login;
use Kotchasan\Database\Sql;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=car-vehicles
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * Query ข้อมูลสำหรับส่งให้กับ DataTable
     *
     * @return \Kotchasan\Database\QueryBuilder
     */
    public static function toDataTable()
    {
        $query = static::createQuery()
            ->from('vehicles R')
            ->where(array('R.published', 1))
            ->order('R.number')
            ->cacheOn();
        $select = array('R.id', 'R.number', 'R.color', 'R.detail');
        $n = 1;
        foreach (Language::get('CAR_SELECT', array()) as $key => $label) {
            $query->join('vehicles_meta M' . $n, 'LEFT', array(array('M' . $n . '.vehicle_id', 'R.id'), array('M' . $n . '.name', $key)));
            $select[] = 'M' . $n . '.value ' . $key;
            ++$n;
        }
        $select[] = 'R.seats';
        return $query->select($select);
    }

    /**
     * Query ยานพาหนะ ใส่ลงใน select
     *
     * @return array
     */
    public static function toSelect()
    {
        $query = static::createQuery()
            ->from('vehicles R')
            ->where(array('R.published', 1))
            ->order('R.number')
            ->cacheOn();
        $n = 1;
        $concat = array('R.`number`');
        foreach (Language::get('CAR_SELECT', array()) as $key => $label) {
            $query->join('vehicles_meta M' . $n, 'LEFT', array(array('M' . $n . '.vehicle_id', 'R.id'), array('M' . $n . '.name', $key)));
            $query->join('category C' . $n, 'LEFT', array(array('C' . $n . '.type', $key), array('C' . $n . '.category_id', 'M' . $n . '.value')));
            $concat[] = '"' . $label . '", C' . $n . '.`topic`';
            ++$n;
        }
        $result = array();
        foreach ($query->select('R.id', Sql::create('CONCAT_WS(" ",' . implode(',', $concat) . ') AS `number`'))->execute() as $item) {
            $result[$item->id] = $item->number;
        }
        return $result;
    }

    /**
     * รับค่าจาก action (vehicles.php)
     *
     * @param Request $request
     */
    public function action(Request $request)
    {
        $ret = array();
        // session, referer, Ajax
        if ($request->initSession() && $request->isReferer() && $request->isAjax()) {
            $action = $request->post('action')->toString();
            if ($action === 'detail') {
                // แสดงรายละเอียด ยานพาหนะ
                $search = \Car\Write\Model::get($request->post('id')->toInt());
                if ($search) {
                    $ret['modal'] = createClass('Car\Detail\View')->vehicle($search);
                }
            } elseif ($action === 'car') {
                $url = WEB_URL . 'index.php?module=car-booking&vehicle_id=' . $request->post('id')->toInt();
                if (Login::isMember()) {
                    // จอง ยานพาหนะ
                    $ret['location'] = $url;
                } else {
                    // login
                    $ret['alert'] = Language::get('Please log in to continue');
                    $ret['location'] = WEB_URL . 'index.php?module=welcome&action=login&ret=' . urlencode($url);
                }
            }
        }
        if (empty($ret)) {
            $ret['alert'] = Language::get('Unable to complete the transaction');
        }
        // คืนค่า JSON
        echo json_encode($ret);
    }
}
