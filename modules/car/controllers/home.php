<?php

/**
 * @filesource modules/car/controllers/home.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Home;

use Kotchasan\Http\Request;

/**
 * module=car-home
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * ฟังก์ชั่นสร้าง card
     *
     * @param Request         $request
     * @param \Kotchasan\Html $card
     * @param array           $login
     */
    public static function addCard(Request $request, $card, $login)
    {
        \Index\Home\Controller::renderCard($card, 'icon-calendar', '{LNG_Book a vehicle}', number_format(\Car\Home\Model::getNew()), '{LNG_Booking today}', 'index.php?module=car-booking');
        \Index\Home\Controller::renderCard($card, 'icon-shipping', '{LNG_Vehicle}', number_format(\Car\Home\Model::cars()), '{LNG_All cars}', 'index.php?module=car-vehicles');
    }

    /**
     * ฟังก์ชั่นสร้าง block
     *
     * @param Request         $request
     * @param \Kotchasan\Html $block
     * @param array           $login
     */
    public static function addBlock(Request $request, $block, $login)
    {
        $content = \Car\Home\View::create()->render($request, $login);
        $block->set('Car calendar', $content);
    }
}
