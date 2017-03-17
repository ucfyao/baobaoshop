<?php
if (!defined('IN_PLUGIN')) {
    exit('Access Denied');
}

if (!$_GET['url_forward']) $_GET['url_forward'] = url('goods/index/index');

$logins = cache('login', '', 'plugin');
if (empty($logins[$_GET['login_code']])) {
    showmessage(lang('login_type_not_exist', 'login#language'));
}
if ($logins[$_GET['login_code']]['enabled'] != 1) {
    showmessage(lang('invalid_login', 'login#language'));
}
$member = model('member/member', 'service')->init();
if ($member['id'] > 0) {
    showmessage(lang('login_again', 'login#language'), $_GET['url_forward'], 0);
}
require_cache(PLUGIN_PATH . 'login/library/login_factory.class.php');

if (is_mobile()) {
    $str = explode('?', urldecode($_SERVER['REQUEST_URI']));
    $url = '/index.php?' . $str[1];
    cookie('url_forward', $url);
} else {
    cookie('url_forward', $_GET['url_forward']);
}

if (IS_POST) {
    $login_factory = new login_factory($logins[$_GET['login_code']]['login_code']);
    $login_url = $login_factory->get_code();
    if (empty($login_url)) showmessage(lang('login_link_error', 'login#language'));

    showmessage(lang('login_get_link_success', 'login#language'), $login_url, 1);
} else {
    if ($_GET['login_code'] == 'wechat_wap' && $logins[$_GET['login_code']]['config']['force'] == 1) {
        $login_factory = new login_factory($logins[$_GET['login_code']]['login_code']);
        $login_url = $login_factory->get_code();
        redirect($login_url);
    }
}