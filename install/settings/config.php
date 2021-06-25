<?php
/* config.php */
return array(
    'version' => '4.0.2',
    'web_title' => 'CRS',
    'web_description' => 'Car Reservation Service',
    'timezone' => 'Asia/Bangkok',
    'member_status' => array(
        0 => 'สมาชิก',
        1 => 'ผู้ดูแลระบบ',
        2 => 'พนักงานขับรถ',
    ),
    'color_status' => array(
        0 => '#259B24',
        1 => '#FF0000',
        2 => '#0E0EDA',
    ),
    'default_icon' => 'icon-shipping',
    'user_forgot' => 0,
    'user_register' => 0,
    'welcome_email' => 0,
    'car_w' => 600,
    'chauffeur_stats' => 2,
    'booking_approving' => 0,
);
