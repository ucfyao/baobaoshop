<?php
class module_notify_hook
{
	/* 控制器方法之前 */
	public function html_load() {
		$load = hd_load::getInstance();
		$queue = $load->librarys('queue');
		$queue->run();
	}
	public function after_register(&$mid){
		$member = array();
		$member['member'] = model('member/member')->where(array('id' => $mid))->find();
		model('notify/notify','service')->execute('after_register', $member);
	}
}