<?php

/**
 * @filesource modules/car/models/chauffeur.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Chauffeur;

/**
 * รายชื่อคนขับรถ
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * @var array
     */
    private $datas = array();

    /**
     * อ่านรายชื่อคนขับรถจากฐานข้อมูล
     * สำหรับการแสดงผล
     *
     * @return \static
     */
    public static function init()
    {
        // Model
        $model = new static();
        // Query
        $query = $model->db()->createQuery()
            ->select('id', 'name')
            ->from('user')
            ->where(array('status', self::$cfg->chauffeur_stats))
            ->cacheOn();
        foreach ($query->execute() as $item) {
            $model->datas[$item->id] = $item->name;
        }
        return $model;
    }

    /**
     * ลิสต์รายการ คนขับรถ
     * สำหรับใส่ลงใน select
     *
     * @return array
     */
    public function toSelect()
    {
        $result = array();
        foreach ($this->datas as $id => $item) {
            $result[$id] = $item;
        }
        return $result;
    }

    /**
     * อ่านชื่อ คนขับรถ จาก $id
     * ไม่พบ คืนค่าว่าง
     *
     * @param int $id
     *
     * @return string
     */
    public function get($id)
    {
        return isset($this->datas[$id]) ? $this->datas[$id] : '';
    }
}
