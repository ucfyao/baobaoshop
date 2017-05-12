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
class wechat_wap extends login_abstract {

	public function __construct($config = array()) {
		if (!empty($config)) $this->set_config($config);
		$this->config['gateway_url'] = "https://open.weixin.qq.com/connect/oauth2/authorize?";
		$this->config['return_url']  = return_url('wechat_wap' , 'login');
	}
	public function getpreparedata(){
		//触发微信返回code码
        $redirectUrl = $this->config['return_url'];
        $url_code = array();
        $url_code["appid"] = $this->config['app_id'];
        $url_code["redirect_uri"] = $redirectUrl;
        $url_code["response_type"] = "code";
        $url_code["scope"] = "snsapi_userinfo";
        $url_code["state"] = "STATE"."#wechat_redirect";
        return $url_code;
    }
	public function _login(){
		 //获取code码，以获取openid
        $code = $_GET['code'];
        $url_openid = array();
        $url_openid["appid"] = $this->config['app_id'];
        $url_openid["secret"] = $this->config['app_key'];
        $url_openid["code"] = $code;
        $url_openid["grant_type"] = "authorization_code";
        $get_openid_url = "https://api.weixin.qq.com/sns/oauth2/access_token?".$this->ToUrlParams($url_openid);
        $openid_info = json_decode(http::postRequest($get_openid_url),TRUE);
        $data = array();
       	$data['access_token'] = $openid_info['access_token'];
       	$data['openid'] = $openid_info['openid'];
       	$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?'.$this->ToUrlParams($data);
       	$info = json_decode(http::getRequest($user_info_url),TRUE);
       	$info['username'] = $info['nickname'];
       	$info['openid'] = $info['unionid'] ? $info['unionid'] : $info['openid'];
       	$info['avatar'] = $info['headimgurl'];
        return $info;
	}

	private function ToUrlParams($urlObj){
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }
}

