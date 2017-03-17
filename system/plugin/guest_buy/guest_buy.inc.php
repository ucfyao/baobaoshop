<?php
if (!defined('IN_PLUGIN')) exit('Access Denied');
if (!IS_POST) {
    $load = hd_load::getInstance();
    $cloud = $load->librarys('cloud');
    $info = $cloud->get_account_info();

    $config = cache('guest_buy', '', 'plugin');
    $type[1] = '手机号码後6位';
    $type[2] = '邮编号码';
    if (1 || $info['sms'] > 1) $type[3] = '随机密码短信通知';
    $type[4] = '自定义';
    include(PLUGIN_PATH . PLUGIN_ID . '/template/index.php');
} else {
    $config['type'] = $_GET['type'];
    $config['custom'] = $_GET['custom'];
    cache('guest_buy', $config, 'plugin');
    showmessage('游客购买配置完成。');
}