<?php
/**
 *      [Haidao] (C)2013-2099 Dmibox Science and technology co., LTD.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.haidao.la
 *      tel:400-600-2042
 */
require_cache(PLUGIN_PATH.PLUGIN_ID.'/library/login_abstract.class.php');
require_cache(PLUGIN_PATH.PLUGIN_ID.'/function/function.php');
class wechat extends login_abstract {

	public function __construct($config = array()) {
		if (!empty($config)) $this->set_config($config);
		$this->config['gateway_url'] = 'https://open.weixin.qq.com/connect/qrconnect?';
		$this->config['return_url']  = return_url('wechat' , 'login');
	}
	public function getpreparedata(){
		// 接口系统级参数
		$prepare_data['appid']        = $this->config['app_id'];
		$prepare_data['scope']        = 'snsapi_login';
		$prepare_data['redirect_uri'] = $this->config['return_url'];
		$prepare_data['response_type'] = 'code';
		$prepare_data['state']    = md5(uniqid(rand(), TRUE));
		cookie('wechat_state',$prepare_data['state']);
		// 排序
		return $prepare_data;
	}
	public function _login(){
		if(cookie('wechat_state') !== $_GET['state']) return FALSE;	
        $_access_info =json_decode($this->access_token($_GET['code']),TRUE);
 		return $this->_getuser($_access_info);
	}

	private function _getuser($info){
		$data = array();
		$data['access_token'] = $info['access_token'];
		$data['openid'] = $info['unionid'] ? $info['unionid'] : $info['openid'];
		$url = 'https://api.weixin.qq.com/sns/userinfo';
		$result = http::getRequest($url,$data);
		$result = array_merge(json_decode($result,TRUE),$info);
		$result['username'] = $result['nickname'];
		$result['avatar'] = $result['headimgurl'];
		return $result;
	}
	
	public function access_token($code){
		$url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->config['app_id'].'&secret='.$this->config['app_key'].'&code='.$code.'&grant_type=authorization_code';
		return http::getRequest($url);
	}
	
	public function _return(){
		return TRUE;
	}
}

