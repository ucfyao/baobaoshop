<?php
/**
 *      [Haidao] (C)2013-2099 Dmibox Science and technology co., LTD.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.haidao.la
 *      tel:400-600-2042
 */
$_GET['m'] = 'plugin';
$_GET['c'] = 'index';
$_filename = basename(__FILE__, '.php');
list(, $method, $driver) = explode(".", $_filename);
$_GET['id'] = 'login:return';
$_GET['login_code'] = $driver;
define('__APP__','../../index.php');
require __APP__;