<?php
//发送短信
function SendSms($data){
    include "TopSdk.php";
    date_default_timezone_set('Asia/Shanghai');
	
	$plugins = cache('plugins');
    $plugins = $plugins[$_GET['mod']];
    $config = cache('hsb_wcash_config', '', 'plugin');

    $appkey = $config['alidayu_appkey']; //appkey
	$appsecret = $config['alidayu_appsecret'];
    $signname = $config['alidayu_signname']; //短信签名

	$templatecode = $config['alidayu_templatecode']; //模板ID
    $mobile = $data['mobile']; //手机号码
	
	$code = $data['code'];
	$product = '申请提现';
	
	if($templatecode && $mobile){

    $c = new TopClient;
    $c->appkey = $appkey;
    $c->secretKey = $appsecret;
    $req = new AlibabaAliqinFcSmsNumSendRequest;
	$req ->setExtend("");
    $req->setSmsType("normal");
    $req->setSmsFreeSignName($signname);
    $req->setSmsParam("{\"code\":\"".$code."\",\"product\":\"".$product."\"}");
    $req->setRecNum($mobile);
    $req->setSmsTemplateCode($templatecode);
    $resp = $c->execute($req);
	
	}
 
    //var_dump($resp);
    if($resp->result->success)
    {
		return true;
    }
    else
    {
        return false;
    }
}