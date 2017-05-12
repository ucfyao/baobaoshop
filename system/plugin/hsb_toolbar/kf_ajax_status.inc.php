<?php
//显示状态
if(!defined('IN_ADMIN')) {
    exit('Access Denied');
}

$id = $_GET['id'];
$is_on = $_GET['is_on'];

		if((int)$id < 1){
			$this->error = lang('_error_');
			return FALSE;
		}
		
$data['kf_status']= $is_on;

$result = model('hsb_toolbar_kf')->where(array('id' => $id))->save($data);
	
if($result === false) {
     showmessage(lang('ajax_status_error','hsb_toolbar#language'), url('admin/app/module',array('mod' => 'hsb_toolbar:kf_list')), 1);
} else {
     showmessage(lang('ajax_status_success','hsb_toolbar#language'), url('admin/app/module',array('mod' => 'hsb_toolbar:kf_list')), 1);
}