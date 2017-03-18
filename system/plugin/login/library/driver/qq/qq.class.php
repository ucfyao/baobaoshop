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
class qq extends login_abstract {
	public function __construct($config = array()) {
		if (!empty($config)) $this->set_config($config);
		$this->config['gateway_url'] = 'https://graph.qq.com/oauth2.0/authorize?';
		$this->config['return_url']  = return_url('qq' , 'login');
	}

	public function getpreparedata(){
		// 接口系统级参数
		$prepare_data['client_id']    = $this->config['app_id'];
		$prepare_data['redirect_uri'] = $this->config['return_url'];
		$prepare_data['response_type'] = 'code';
		$prepare_data['scope'] = 'get_user_info';
		$prepare_data['state'] = md5(uniqid(rand(), TRUE));
		if(defined('IS_MOBILE')){
			$prepare_data['display'] = 'mobile';
		}
		cookie('qq_state',$prepare_data['state']);
		return $prepare_data;
	}

	public function _login(){
//		if(cookie('qq_state') !== $_GET['state']) return FALSE;
        $_access_info = $this->access_token($this->config['return_url'],$_GET['code']);
        $_access_token = substr($_access_info, strpos($_access_info, '=')+1,strpos($_access_info, '&')-strpos($_access_info, '=')-1);
        $user_info = $this->get_openid($_access_token);
        $uid_json = json_decode(substr($user_info, strpos($user_info, '{'),strpos($user_info, ')')-strpos($user_info, '{')),TRUE);
        $u_info=array();
        $u_info['openid'] = $uid_json['openid'];
        $_user_arr = json_decode($this->get_user_info($_access_token,$u_info['openid']),TRUE);
        $u_info['username'] = $_user_arr['nickname'];
		$u_info['avatar'] = $_user_arr['figureurl_qq_2'];
        return $u_info;
	}
	//获取access token
	public function access_token($callback_url, $code){
		$url = 'https://graph.qq.com/oauth2.0/token?client_id='.$this->config['app_id'].'&client_secret='.$this->config['app_key'].'&code='.$code.'&grant_type=authorization_code&redirect_uri='.$callback_url;
		return http::getRequest($url);
	}
	public function get_openid($token){
		$url =	'https://graph.qq.com/oauth2.0/me?access_token='.$token;
		return http::getRequest($url);
	}
	public function get_user_info($token,$uid){
		$url = 'https://graph.qq.com/user/get_user_info?access_token='.$token.'&oauth_consumer_key='.$this->config['app_id'].'&openid='.$uid;
		return http::getRequest($url);
	}
}

