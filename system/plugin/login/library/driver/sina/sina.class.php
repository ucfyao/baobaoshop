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
class sina extends login_abstract {
	public function __construct($config = array()) {
		if (!empty($config)) $this->set_config($config);
		$this->config['gateway_url'] = 'https://api.weibo.com/oauth2/authorize?';
		$this->config['return_url']  = return_url('sina' , 'login');
	}

	public function getpreparedata(){
		// 接口系统级参数
		$prepare_data['client_id']    = $this->config['app_key'];
		$prepare_data['redirect_uri'] = $this->config['return_url'];
		return $prepare_data;
	}

	public function _login(){
        $_access_info = $this->access_token($this->config['return_url'],$_GET['code']);
 		$info = json_decode($_access_info,TRUE);
 		return $this->_getuser($info);
	}

	private function _getuser($info){
		$data = array();
		$data['access_token'] = $info['access_token'];
		$data['uid'] = $info['uid'];
		$url = 'https://api.weibo.com/2/users/show.json';
		$result = http::getRequest($url,$data);
		$result = array_merge(json_decode($result,TRUE),$info);
		$result['username'] = $result['name'];
		$result['avatar'] = $result['avatar_large'];
		$result['openid'] = $result['uid'];
		return $result;
	}
	//获取access token
	public function access_token($callback_url, $code){
		$params=array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>$this->config['app_key'],
			'client_secret'=>$this->config['app_secret'],
			'redirect_uri'=>$callback_url
		);
		$url='https://api.weibo.com/oauth2/access_token?'.http_build_query($params);
		return http::postRequest($url);
	}
}

