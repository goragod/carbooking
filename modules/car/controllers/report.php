<?php

/**
 * @filesource modules/car/controllers/report.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Report;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=car-report
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * รายงานการจอง (admin)
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        // ค่าที่ส่งมา
        $params = array(
            'from' => $request->request('from')->date(),
            'vehicle_id' => $request->request('vehicle_id')->toInt(),
            'chauffeur' => $request->request('chauffeur', -2)->toInt(),
            'status' => $request->request('status', $request->cookie('carReport_status', -1)->toInt())->toInt(),
            'statuses' => Language::get('CAR_BOOKING_STATUS'),
        );
        setcookie('carReport_status', $params['status'], time() + 2592000, '/', HOST, HTTPS, true);
        // ข้อความ title bar
        $this->title = Language::get('Book a vehicle');
        // เลือกเมนู
        $this->menu = 'report';
        // สมาชิก
        $login = Login::isMember();
        // สามารถอนุมัติห้องประชุมได้
        if (Login::checkPermission($login, 'can_approve_car')) {
            // ข้อความ title bar
            if (isset($params['statuses'][$params['status']])) {
                $title = $params['statuses'][$params['status']];
                $this->title .= ' ' . $title;
            }
            // แสดงผล
            $section = Html::create('section', array(
                'class' => 'content_bg',
            ));
            // breadcrumbs
            $breadcrumbs = $section->add('div', array(
                'class' => 'breadcrumbs',
            ));
            $ul = $breadcrumbs->add('ul');
            $ul->appendChild('<li><span class="icon-shipping">{LNG_Vehicle}</span></li>');
            $ul->appendChild('<li><span>{LNG_Report}</span></li>');
            if (isset($title)) {
                $ul->appendChild('<li><span>' . $title . '</span></li>');
            }
            $section->add('header', array(
                'innerHTML' => '<h2 class="icon-report">' . $this->title . '</h2>',
            ));
            // menu
            $section->appendChild(\Index\Tabmenus\View::render($request, 'report', 'vehicle'));
            // แสดงตาราง
            $section->appendChild(\Car\Report\View::create()->render($request, $params));
            // คืนค่า HTML
            return $section->render();
        }
        // 404
        return \Index\Error\Controller::execute($this, $request->getUri());
    }
}
