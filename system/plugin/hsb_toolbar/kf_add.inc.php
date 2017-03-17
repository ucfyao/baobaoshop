<?php
//添加客服
if(!defined('IN_ADMIN')) {
    exit('Access Denied');
}

require_cache(PLUGIN_PATH . PLUGIN_ID . '/hooks/function/function.php'); //接入公共参数

if (IS_POST) {
$data = array();
$data['kf_name'] = remove_xss($_GET['kf_name']);
$data['kf_no'] = remove_xss($_GET['kf_no']);
$data['kf_type'] = remove_xss($_GET['kf_type']);
$data['kf_sort'] = remove_xss($_GET['kf_sort']);
$data['kf_status'] = remove_xss($_GET['kf_status']);
$result_add = model('hsb_toolbar_kf')->add($data);
if (!$result_add) showmessage(lang('post_message_error', 'hsb_toolbar#language'));
showmessage(lang('post_message_success', 'hsb_toolbar#language'), url('admin/app/module',array('mod' => 'hsb_toolbar:kf_list')), 1);

} else {

 $kf_type = getkftype();
 include PLUGIN_PATH . PLUGIN_ID . '/template/kf_add.html';

}