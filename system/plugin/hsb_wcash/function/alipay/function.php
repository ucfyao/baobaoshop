<?php
//发送短信
function alipay_autopay($data){
	
    include "AopSdk.php";
    date_default_timezone_set('Asia/Shanghai');
	
	$plugins = cache('plugins');
    $plugins = $plugins[$_GET['mod']];
	$config = cache('hsb_wcash_config', '', 'plugin');
	
	$get_wcash = model('hsb_wcash_log')->where(array('id' => $data['id']))->find();
	
    $aop = new AopClient ();
    $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
    $aop->appId = $config['alipay_appId'];
    $aop->rsaPrivateKey = $config['alipay_rsaPrivateKey'];
    $aop->alipayrsaPublicKey = $config['alipay_alipayrsaPublicKey'];
    $aop->apiVersion = '1.0';
    $aop->signType = 'RSA2';
    $aop->postCharset='UTF-8';
    $aop->format='json';
    $request = new AlipayFundTransToaccountTransferRequest ();
    $request->setBizContent("{" .
    "    \"out_biz_no\":\"".$get_wcash['out_biz_no']."\"," . //实时支付订单号
    "    \"payee_type\":\"ALIPAY_LOGONID\"," .  
    "    \"payee_account\":\"".$get_wcash['account']."\"," .//收款方账户
    "    \"amount\":\"".$get_wcash['go_money']."\"," . //转账金额
   // "    \"payer_real_name\":\"付款方真实姓名\"," .
    "    \"payer_show_name\":\"".$config['alipay_payer_show_name']."\"," .
    "    \"payee_real_name\":\"".$get_wcash['true_name']."\"," . //收款方真实姓名
    "    \"remark\":\"余额提现-实时付款\"," . //转账备注
    "    \"ext_param\":\"{\\\"order_title\\\":\\\"余额提现-实时付款\\\"}\"" . //扩展参数，json字符串格式，目前仅支持的key：order_title：收款方转账账单标题。 用于商户的特定业务信息的传递，只有商户与支付宝约定了传递此参数且约定了参数含义，此参数才有效。
    "  }");
    $result = $aop->execute ( $request); 
 
    $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
    //$resultCode = $result->$responseNode->code;
    
	$callback = $result->$responseNode;
	$callback = (array)$callback;  
    return $callback;
}