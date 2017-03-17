<?php
if(!defined('IN_PLUGIN')) {
  exit('Access Denied');
}
$member = model('member/member','service')->init();
if(IS_POST){
	$data['member_id'] = $member['id'];
	$data['type'] = $_GET['login_code']; 
	$result = model('login#member_oauth')->where($data)->find();
	if($result){
		showmessage(lang('login_bind_alread','login#language'),'',0);
	}
	$logins = cache('login','','plugin');
	require_cache(PLUGIN_PATH.PLUGIN_ID.'/library/login_factory.class.php');
    $login_factory = new login_factory($logins[$_GET['login_code']]['login_code']);
    $login_url = $login_factory->get_code();
    if (empty($login_url)) showmessage(lang('login_link_error','login#language'));
    showmessage(lang('login_get_link_success','login#language') , $login_url, 1);
}else{
	$logins = cache('login','','plugin');
	$bind = model('#login#member_oauth')->where(array('member_id' => $member['id']))->getfield('type,account_name',TRUE);
	foreach ($logins as $key => $login) {
		if($login['enabled'] != 1){
			unset($logins[$key]);
			continue;
		} 
		$logins[$key]['account_name'] = $bind[$key];
	}
	$SEO = seo('第三方登录账号绑定');
	if(defined('MOBILE')){
		include PLUGIN_PATH.PLUGIN_ID.'/template/wap/third_login_bind.html';
	}else{
		include PLUGIN_PATH.PLUGIN_ID.'/template/pc/third_login_bind.html';
	}
}