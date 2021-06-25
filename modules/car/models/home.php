<?php

/**
 * @filesource modules/car/models/home.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Home;

use Kotchasan\Database\Sql;

/**
 * โมเดลสำหรับอ่านข้อมูลแสดงในหน้า  Home
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * อ่านรายการจองวันนี้
     *
     * @return object
     */
    public static function getNew()
    {
        $search = static::createQuery()
            ->selectCount()
            ->from('car_reservation')
            ->where(array(
                array('status', 1),
                Sql::BETWEEN(date('Y-m-d'), Sql::DATE('begin'), Sql::DATE('end')),
            ))
            ->execute();
        if (!empty($search)) {
            return $search[0]->count;
        }
        return 0;
    }

    /**
     * จำนวนรถยนต์ทั้งหมดที่สามารถจองได้
     *
     * @return object
     */
    public static function cars()
    {
        $search = static::createQuery()
            ->selectCount()
            ->from('vehicles')
            ->where(array('published', 1))
            ->execute();
        if (!empty($search)) {
            return $search[0]->count;
        }
        return 0;
    }
}
