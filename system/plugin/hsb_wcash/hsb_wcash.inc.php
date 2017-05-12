<?php
if (!defined('IN_ADMIN')) {
    exit('Access Denied');
}
$plugins = cache('plugins');
$plugins = $plugins[$_GET['mod']];
$config = cache('hsb_wcash_config', '', 'plugin');

$act = $_GET['act'];

if (IS_POST) {
    // 配置
    $result_add = cache('hsb_wcash_config', $_GET, 'plugin');
	
	if (!$result_add) showmessage(lang('post_message_error', 'hsb_wcash#language'));
	showmessage(lang('post_message_success', 'hsb_wcash#language'), url('admin/app/module',array('mod' => 'hsb_wcash:hsb_wcash','act' => $act)), 1);

} else {
    include(PLUGIN_PATH . PLUGIN_ID . '/template/config.php');
}