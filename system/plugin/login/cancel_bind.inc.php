<?php
if(!defined('IN_PLUGIN')) {
  exit('Access Denied');
}
$member = model('member/member','service')->init();
if(IS_POST){
	$data = array();
	$data['member_id'] = $member['id'];
	$data['type'] = $_GET['login_code']; 
	$result = model('#login#member_oauth')->where($data)->delete();
	if(!$result){
		showmessage(model('#login#member_oauth')->getError(),'',0);
	}else{
		showmessage(lang('_operation_success_'),'',1);
	}
}