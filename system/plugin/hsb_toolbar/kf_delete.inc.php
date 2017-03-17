<?php
//删除
if(!defined('IN_ADMIN')) {
    exit('Access Denied');
}

$ids = (array) $_GET['id'];

if(empty($ids)) {
    showmessage(lang('_error_'));
}

$_map = array();

if(is_array($ids)) {
	$_map['id'] = array("IN", $ids);
} else {
	$_map['id'] = $ids;
}

$result = model('hsb_toolbar_kf')->where($_map)->delete();
	
if($result === false) {
     showmessage(lang('delete_error','hsb_toolbar#language'), url('admin/app/module',array('mod' => 'hsb_toolbar:kf_list')), 1);
} else {
     showmessage(lang('delete_success','hsb_toolbar#language'), url('admin/app/module',array('mod' => 'hsb_toolbar:kf_list')), 1);
}