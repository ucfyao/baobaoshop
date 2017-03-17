<?php
if (!defined('IN_ADMIN')) {
    exit('Access Denied');
}
$plugins = cache('plugins');
$plugins = $plugins[$_GET['mod']];
$config = cache('reg_gift', '', 'plugin');

if (IS_POST) {
    $_POST['start_time'] = strtotime($_POST['start_time']);
    $_POST['end_time'] = strtotime($_POST['end_time']);
    $_POST['is_open'] = (string)$_POST['is_open'];
    if(!is_numeric($_POST['exp'])){
         showmessage(lang('exp_error','reg_gift#language'));
    }else{

        if($_POST['exp'] && $_POST['exp']<0){
            showmessage(lang('num_error','reg_gift#language'));
        }
    }

    $_POST['exp'] = (int)$_POST['exp'];
    if(!is_numeric($_POST['money'])){
        showmessage(lang('money_error','reg_gift#language'));
    }else{

        if($_POST['money'] && $_POST['money']<0){
           showmessage(lang('num_error','reg_gift#language'));
        }
    }
    $_POST['money'] =$_POST['money'];
    $_POST['roll'] = (int)$_POST['roll'];
    extract($_POST);
    if ($start_time > $end_time) showmessage(lang('time_error','reg_gift#language'));
    if (empty($exp) && empty($money) && empty($roll)) showmessage(lang('type_error','reg_gift#language'));
    if ($roll)   showmessage(lang('roll_error','reg_gift#language'));
    $config = cache('reg_gift', $_POST, 'plugin');
    if ($config == ture) {
        showmessage(lang('finish_config','reg_gift#language'));
    } else {
        showmessage(lang('errer_config','reg_gift#language'));
    }
} else {
    $arr = model('app')->where(array('identifier' => 'module.coupon', 'available' => 1))->find();
    $config['iscoupon'] = $arr;
    if ($arr) {
        if (substr($arr['version'],0) == 1) {
            if (substr($arr['version'], -1) < 3) {
                $config['iscoupon'] = false;
            }
        }
    }
    include(PLUGIN_PATH . PLUGIN_ID . '/template/index.php');
}
      
  