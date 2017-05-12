<?php 

function wxpay_autopay($paydata) {

	$plugins = cache('plugins');
    $plugins = $plugins[$_GET['mod']];
	$config = cache('hsb_wcash_config', '', 'plugin');
	
	$get_wcash = model('hsb_wcash_log')->where(array('id' => $paydata['id']))->find();
	
	$get_openid = model('member_oauth')->where(array('member_id' => $get_wcash['member_id']))->find();
	
	$data['mch_appid'] = $config['wxpay_mch_appid'];//商户的应用appid
	$data['mchid'] = $config['wxpay_mchid'];//商户ID
	$data['nonce_str'] = rand(100000, 999999);//unicode();//这个据说是唯一的字符串下面有方法
	$data['partner_trade_no'] = $get_wcash['out_biz_no'];//.time();//这个是订单号。
	$data['openid'] = $get_openid['openid'];//这个是授权用户的openid。。这个必须得是用户授权才能用
	$data['check_name'] = 'OPTION_CHECK';//这个是设置是否检测用户真实姓名的
	$data['re_user_name'] = $get_wcash['true_name'];//用户的真实名字
	$data['amount'] = $get_wcash['go_money']*100;//提现金额（单位为：分）
	$data['desc'] = '余额提现-实时付款';//订单描述
	$data['spbill_create_ip'] = get_client_ip();//这个最烦了，，还得获取服务器的ip
	$secrect_key = $config['wxpay_key'];///这个就是个API密码。32位的。。随便MD5一下就可以了
	$data=array_filter($data);
	ksort($data);
	$str='';
	foreach($data as $k=>$v) {
    $str.=$k.'='.$v.'&';
	}
	$str.='key='.$secrect_key;
	$data['sign']=md5($str);
	$xml=arraytoxml($data);

	$url='https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
	$res=curl($xml,$url);
	$return=xmltoarray($res);
    
	return $return;

}

function unicode() {
    $str = uniqid(mt_rand(),1);
    $str=sha1($str);
   return md5($str);
}

function arraytoxml($data){
    $str='<xml>';
    foreach($data as $k=>$v) {
        $str.='<'.$k.'>'.$v.'</'.$k.'>';
    }
    $str.='</xml>';
    return $str;
}

function xmltoarray($xml) { 
     //禁止引用外部xml实体 
    libxml_disable_entity_loader(true); 
    $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA); 
    $val = json_decode(json_encode($xmlstring),true); 
    return $val;
} 

function curl($param="",$url) {
    $plugins = cache('plugins');
    $plugins = $plugins[$_GET['mod']];
	$config = cache('hsb_wcash_config', '', 'plugin');
	
	$apiclient_dir = $config['wxpay_apiclient_dir'];
	
    $postUrl = $url;
    $curlPost = $param;
    $ch = curl_init();                                      //初始化curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);                 //抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);                    //设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);                      //post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);           // 增加 HTTP Header（头）里的字段 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);        // 终止从服务端进行验证
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch,CURLOPT_SSLCERT,getcwd().$apiclient_dir.'/apiclient_cert.pem'); //这个是证书的位置
    curl_setopt($ch,CURLOPT_SSLKEY,getcwd().$apiclient_dir.'/apiclient_key.pem'); //这个也是证书的位置
    $data = curl_exec($ch);                                 //运行curl
    curl_close($ch);
    return $data;
}