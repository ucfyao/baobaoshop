<?php
class plugin_login_hook extends plugin{
	public function login_box_footer(){
		$logins = cache('login','','plugin');
        $url_forward = $_GET['url_forward'] ? $_GET['url_forward'] : url('goods/index/index');
		$html = '';
		foreach ($logins as $key => $login) {
		    if(!in_array($key,$logins['switchs'])) continue;
			if($login['enabled'] == 1 && $login['login_code'] != 'wechat_wap'){
				$html .= '<a href="javascript:void(0)" class="third_login login-'.$login['login_code'].'" title="'.$login['login_name'].'" login-code="'.$login['login_code'].'">'.$login['login_name'].'</a>&nbsp;&nbsp;&nbsp;';
			}
		}
		$html .= '<script type="text/javascript">';
		$html .= '$(".third_login").bind("click",function(){';
		$html .=	'var login_code = $(this).attr("login-code");';
		$html .=	'var url_forward = "'.$url_forward.'";';
		$html .=	'$.post("plugin.php?id=login:third_login",{login_code : login_code,url_forward : url_forward},function(ret){';
		$html .=	'if (ret.status != 1) {';
		$html .=		'$.tips({';
		$html .=			'icon:"error",';
		$html .=			'content:ret.message';
		$html .=		'});';
		$html .=		'location.href = "'.url('member/member/index').'"';
		$html .=	'} else {';
		$html .=		'location.href = ret.referer;';
		$html .=	'}},"json")';
		$html .='})';
		$html .= '</script>';
		return $html;
	}
	public function menu_account_extra_menu(){
		$data = array();
		$data['m'] = 'plugin';
		$data['c'] = 'index';
		$data['id'] = 'login:third_login_bind';
		$data['name'] = '帐号绑定';
		return $data;
	}
	public function wap_login_footer(){
		$logins = cache('login','','plugin');
        $url_forward = $_GET['url_forward'] ? $_GET['url_forward'] : url('goods/index/index');
		$html  = ' <ul class="other-login">';
		foreach ($logins as $key => $login) {
			if($login['enabled'] == 1 && $login['login_code'] != 'wechat' && $login['login_code'] != 'wechat_wap'){
				$html .= '<li><a class="login-item login_'.$login['login_code'].'" login-code="'.$login['login_code'].'" href="javascript:;"></a><em class="mui-pull-right">|</em></li>';
			}
		}
		if(defined('IS_WECHAT')){
			$html .= '<li><a class="login-item login_'.$logins['wechat_wap']['login_code'].'" login-code="'.$logins['wechat_wap']['login_code'].'" href="javascript:;"></a><em class="mui-pull-right">|</em></li>';
		}
		$html .= '</ul>';
		$html .= '<script type="text/javascript">';
		$html .= 'mui(".other-login").on("tap",".login-item",function(){';
		$html .= 'var login_code = $(this).attr("login-code");';
        $html .= 'var url_forward = "'.$url_forward.'";';
		$html .= '$.post("plugin.php?id=login:third_login",{login_code:login_code,url_forward:url_forward},function(ret){';
		$html .= 'if (ret.status != 1) {';
		$html .= '$.tips({';
		$html .= 'content:ret.message,';
		$html .= 'callback:function() {';
		$html .= 'return false;';
		$html .= '}';
		$html .= '});';
		$html .= '} else {';
		$html .= 'window.location.href = ret.referer;';
		$html .= '}';
		$html .= '},"json")';
		$html .= '})';
		$html .= ' </script>';
		return $html;
	}
	public function wap_member_index_extra_info(){
		$data = array();
		$data['url'] = url('plugin/index/index',array('id' => 'login:third_login_bind'));
		$data['name'] = '帐号绑定';
		$data['desc'] = '修改绑定';
		$data['ico'] = __ROOT__.'system/plugin/login/statics/images/ico_bind.png';
		return $data;
	}

    /**
     * 微信端第三方强制微信登录
     */
    public function no_login(){
        $member = model('member/member','service')->init();
        if($member['id']) return ;
        $logins = cache('login','','plugin');
        if(defined('IS_WECHAT') && $logins['wechat_wap']['config']['force'] == 1 && $logins['wechat_wap']['enabled'] == 1){
            $str = explode('?', urldecode($_SERVER['REQUEST_URI']));
            $url = '/index.php?'.$str[1];
            redirect(url('plugin/index/index',array('id' => 'login:third_login','login_code' => 'wechat_wap','url_forward' => $url)));
        }
    }

    /**
     * 登录开关[全局 - 注册与访问 - 可用第三方登录方式]
     * @return string
     */
    public function set_reg_extra()
    {
        $config = cache('login','','plugin');
        $html = form::input('checkbox', 'switchs[]', $config['switchs'], '可用第三方登录方式：', '可以选择开启关闭不同的第三方登录方式以供用户登录。', array('items' => array('alipay'=>'支付宝', 'qq'=>'QQ', 'sina'=>'新浪微博', 'wechat'=>'微信', 'wechat_wap'=>'微信手机')));
        echo $html;
	}

    /**
     * 将设置的数据加入插件缓存
     */
    public function before_set_reg()
    {
        $config = cache('login','','plugin');
        if(!$_GET['switchs']){
            $config['switchs'] = '';
        }else{
            $config['switchs'] = $_GET['switchs'];
        }
        cache('login',$config,'plugin');
	}
}