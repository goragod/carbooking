<?php

/**
 * @filesource modules/car/models/email.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Email;

use Kotchasan\Date;
use Kotchasan\Language;

/**
 * ส่งอีเมลไปยังผู้ที่เกี่ยวข้อง
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\KBase
{
    /**
     * ส่งอีเมลแจ้งการทำรายการ
     *
     * @param string $mailto อีเมล
     * @param string $name   ชื่อ
     * @param array  $order ข้อมูล
     *
     * @return string
     */
    public static function send($mailto, $name, $order)
    {
        // ข้อความ
        $msg = array(
            '{LNG_Book a vehicle}',
            '{LNG_Contact name}: ' . $name,
            '{LNG_Vehicle usage details}: ' . $order['detail'],
            '{LNG_Date}: ' . Date::format($order['begin'], 'd M Y H:i') . ' - ' . Date::format($order['end'], 'd M Y H:i'),
            '{LNG_Status}: ' . Language::find('CAR_BOOKING_STATUS', null, $order['status']),
            '{LNG_Chauffeur}: ' . \Car\Chauffeur\Model::init()->get($order['chauffeur']),
            'URL: ' . WEB_URL . 'index.php?module=car',
        );
        $msg = Language::trans(implode("\n", $msg));
        $admin_msg = $msg . '-order&id=' . $order['id'];
        $ret = array();
        if (self::$cfg->noreply_email != '') {
            $subject = '[' . self::$cfg->web_title . '] ' . Language::get('Book a vehicle');
            // ส่งอีเมลไปยังผู้ทำรายการเสมอ
            $err = \Kotchasan\Email::send($name . '<' . $mailto . '>', self::$cfg->noreply_email, $subject, nl2br($msg));
            if ($err->error()) {
                $ret[] = strip_tags($err->getErrorMessage());
            }
            // อีเมลของมาชิกที่สามารถอนุมัติได้ทั้งหมด
            $where = array(
                array('status', 1),
                array('permission', 'LIKE', '%,can_approve_car,%'),
            );
            // คนขับรถ
            if ($order['chauffeur'] > 0) {
                $where[] = array('id', $order['chauffeur']);
            }
            $query = \Kotchasan\Model::createQuery()
                ->select('username', 'name')
                ->from('user')
                ->where(array(
                    array('social', 0),
                    array('active', 1),
                ))
                ->andWhere($where, 'OR')
                ->cacheOn();
            foreach ($query->execute() as $item) {
                // ส่งอีเมล
                $err = \Kotchasan\Email::send($item->name . '<' . $item->username . '>', self::$cfg->noreply_email, $subject, nl2br($admin_msg));
                if ($err->error()) {
                    $ret[] = strip_tags($err->getErrorMessage());
                }
            }
        }
        if (!empty(self::$cfg->line_api_key)) {
            // ส่งไลน์
            $err = \Gcms\Line::send($admin_msg);
            if ($err != '') {
                $ret[] = $err;
            }
        }
        if (empty($ret)) {
            // คืนค่า
            return Language::get('Your message was sent successfully');
        } else {
            return implode("\n", $ret);
        }
    }
}
