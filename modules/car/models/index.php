<?php

/**
 * @filesource modules/car/models/index.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Index;

use Gcms\Login;
use Kotchasan\Database\Sql;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=car
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
     * @param int $member_id
     *
     * @return \Kotchasan\Database\QueryBuilder
     */
    public static function toDataTable($member_id)
    {
        $sql = Sql::create('(CASE WHEN NOW() BETWEEN V.`begin` AND V.`end` THEN 1 WHEN NOW() > V.`end` THEN 2 ELSE 0 END) AS `today`');
        $query = static::createQuery()
            ->from('car_reservation V')
            ->join('vehicles R', 'INNER', array('R.id', 'V.vehicle_id'))
            ->where(array('V.member_id', $member_id));
        $concat = array('R.`number`');
        $n = 1;
        foreach (Language::get('CAR_SELECT', array()) as $key => $label) {
            $query->join('vehicles_meta M' . $n, 'LEFT', array(array('M' . $n . '.vehicle_id', 'R.id'), array('M' . $n . '.name', $key)));
            $query->join('category C' . $n, 'LEFT', array(array('C' . $n . '.type', $key), array('C' . $n . '.category_id', 'M' . $n . '.value')));
            $concat[] = '"' . $label . '", C' . $n . '.`topic`';
            ++$n;
        }
        return $query->select('V.id', 'V.detail', 'V.vehicle_id', Sql::create('CONCAT_WS(" ",' . implode(',', $concat) . ') AS `number`'), 'V.begin', 'V.end', 'V.status', 'V.reason', $sql, 'R.color');
    }

    /**
     * รับค่าจาก action (index.php)
     *
     * @param Request $request
     */
    public function action(Request $request)
    {
        $ret = array();
        // session, referer, สมาชิก
        if ($request->initSession() && $request->isReferer()) {
            $action = $request->post('action')->toString();
            if ($action === 'cancel' && Login::isMember()) {
                // ยกเลิกการจอง
                $car_reservation_table = $this->getTableName('car_reservation');
                $search = $this->db()->first($car_reservation_table, $request->post('id')->toInt());
                if ($search && $search->status == 0) {
                    // ลบ
                    $this->db()->delete($car_reservation_table, $search->id);
                    $this->db()->delete($this->getTableName('car_reservation_data'), array('reservation_id', $search->id), 0);
                    // คืนค่า
                    $ret['alert'] = Language::get('Canceled successfully');
                    $ret['remove'] = 'datatable_' . $search->id;
                }
            } elseif ($action === 'detail') {
                // แสดงรายละเอียดการจอง
                $search = $this->bookDetail($request->post('id')->toInt());
                if ($search) {
                    $ret['modal'] = createClass('Car\Detail\View')->bookDetail($search);
                }
            }
        }
        if (empty($ret)) {
            $ret['alert'] = Language::get('Unable to complete the transaction');
        }
        // คืนค่า JSON
        echo json_encode($ret);
    }

    /**
     * อ่านข้อมูลรายการที่เลือก
     * คืนค่าข้อมูล object ไม่พบคืนค่า null.
     *
     * @param int $id
     *
     * @return object|null
     */
    public function bookDetail($id)
    {
        $query = $this->db()->createQuery()
            ->from('car_reservation V')
            ->join('vehicles R', 'LEFT', array('R.id', 'V.vehicle_id'))
            ->join('user U', 'LEFT', array('U.id', 'V.member_id'))
            ->join('user U2', 'LEFT', array('U2.id', 'V.chauffeur'))
            ->where(array('V.id', $id));
        $select = array('V.*', 'R.number', 'U2.name chauffeur_name', 'U2.phone chauffeur_phone', 'U.name contact', 'U.phone', 'R.color', 'R.seats');
        $n = 1;
        foreach (Language::get('CAR_SELECT', array()) as $key => $label) {
            $query->join('vehicles_meta M' . $n, 'LEFT', array(array('M' . $n . '.vehicle_id', 'R.id'), array('M' . $n . '.name', $key)));
            $select[] = 'M' . $n . '.value ' . $key;
            ++$n;
        }
        foreach (Language::get('CAR_OPTIONS', array()) as $key => $label) {
            $query->join('car_reservation_data M' . $n, 'LEFT', array(array('M' . $n . '.reservation_id', 'V.id'), array('M' . $n . '.name', $key)));
            $select[] = 'M' . $n . '.value ' . $key;
            ++$n;
        }
        return $query->first($select);
    }
}
