<?php
class plugin_hsb_wcash_hook {

	public function menu_account_extra_menu() {
        
		$data = array();
		$data['m'] = 'plugin';
		$data['c'] = 'index';
		$data['id'] = 'hsb_wcash:index';
	    $data['name'] = '余额提现';
	    return $data;
	}//menu_account_extra_menu
	
	public function wap_member_index_extra_info(){
		$data = array();
		$data['url'] = url('plugin/index/index',array('id' => 'hsb_wcash:index'));
		$data['name'] = '余额提现';
		$data['desc'] = '账户余额提现';
		$data['ico'] = __ROOT__.'system/plugin/hsb_wcash/statics/images/ico_wcash.png';
		return $data;

	}//wap_member_index_extra_info
	
}