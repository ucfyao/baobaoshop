<?php
/**
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */
$_filename = basename(__FILE__, '.php');
list(, $method, $driver) = explode(".", $_filename);
define('_PAYMENT_', $driver);
$_GET['m'] = 'pay';
$_GET['c'] = 'index';
$_GET['a'] = 'd'.$method;
include dirname(__FILE__).'/../../index.php';