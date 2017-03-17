<?php
function return_url($code, $method = 'login') {
    return (is_ssl() ? 'https://':'http://').$_SERVER['HTTP_HOST'].'/api/login/api.'.$method.'.'.$code.'.php';
}
function dologin($mid, $password) {
	$auth = authcode($mid."\t".$password, 'ENCODE');
	cookie('member_auth', $auth, 86400);
	$login_info = array(
		'id' => $mid,
		'login_time' => TIMESTAMP,
		'login_ip'	=> get_client_ip(),
	);
	model('member/member')->update($login_info);
    return true;
}
function login_inc($mid){
    model('member/member')->where(array('id' => $mid))->setInc('login_num');
    return true;
}
/**
 * 生成签名结果
 * @param $array要加密的数组
 * @param return 签名结果字符串
*/
function build_mysign($sort_array,$security_code,$sign_type = "MD5", $issort = TRUE) {
    if($issort == TRUE) {
	    $sort_array = arg_sort($sort_array);
	}
    $prestr = create_linkstring($sort_array);
    $prestr = $prestr.$security_code;
    $mysgin = sign($prestr,$sign_type);
    return $mysgin;
}


/**
 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
 * @param $array 需要拼接的数组
 * @param return 拼接完成以后的字符串
*/
function create_linkstring($array, $encode = FALSE) {
    $arg  = "";
    while (list ($key, $val) = each ($array)) {
        if($encode === TRUE) $val = urlencode($val);
        $arg.=$key."=".$val."&";
    }
    $arg = substr($arg,0,count($arg)-2);//去掉最后一个&字符
    return $arg;
}

/********************************************************************************/

/**除去数组中的空值和签名参数
 * @param $parameter 加密参数组
 * @param return 去掉空值与签名参数后的新加密参数组
 */
function para_filter($parameter) {
    $para = array();
    while (list ($key, $val) = each ($parameter)) {
        if($key == "sign" || $key == "sign_type" || $val == "")continue;
        else    $para[$key] = $parameter[$key];
    }
    return $para;
}

/********************************************************************************/

/**对数组排序
 * @param $array 排序前的数组
 * @param return 排序后的数组
 */
function arg_sort($array) {
    $array = para_filter($array);
    ksort($array);
    reset($array);
    return $array;
}

/********************************************************************************/

/**加密字符串
 * @param $prestr 需要加密的字符串
 * @param return 加密结果
 */
function sign($prestr,$sign_type) {
    return md5($prestr);
}

/*
*xml to array
*/
function xmlToArray($xml) {
    $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $array_data;
}

/*
*array to xml
*/
function arrayToXml($arr) {
    $xml = "<xml>";
    foreach ($arr as $key=>$val) {
         $xml.="<".$key.">".$val."</".$key.">";
    }
    $xml.="</xml>";
    return $xml;
}
/*
*生成32位随机字符串
*/
function createNoncestr( $length = 32 ) 
{
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
    $str ="";
    for ( $i = 0; $i < $length; $i++ )  {  
        $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
    }  
    return $str;
}