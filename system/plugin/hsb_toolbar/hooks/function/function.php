<?php
//显示客服类型
function getkftype($kf_type){
    $kf_types = array(
	  '0' => 'QQ客服',
	  '1' => '阿里旺旺',
	);
	if($kf_type ==''){
	$kf_types = $kf_types;
	}else{
	$kf_types = $kf_types[$kf_type];
	}
    return $kf_types;     
}

//显示客服在线状态
function getkfol($kf_type,$kf_no,$kf_name){
    $config = cache('hsb_toolbar_config', '', 'plugin');
	
	if($config['ol_status']){
      $kf_ol = array(
	    '0' => '<a href="tencent://message/?Uin='.$kf_no.'&Site=&Menu=yes" target="_blank"><em></em>'.$kf_name.'</a>', //QQ代码
	    '1' => '<a target="_blank" href="http://amos.alicdn.com/msg.aw?v=2&uid='.$kf_no.'&site=cnalichn&s=11&charset=gbk" ><em></em>'.$kf_name.'</a>', //阿里旺旺代码
	  );
	}else{
	  $kf_ol = array(
	  '0' => '<a href="tencent://message/?Uin='.$kf_no.'&Site=&Menu=yes" target="_blank"><span><img border="0" src="http://wpa.qq.com/pa?p=2:'.$kf_no.':52"/></span>'.$kf_name.'</a>', //QQ代码
	  '1' => '<a target="_blank" href="http://amos.alicdn.com/msg.aw?v=2&uid='.$kf_no.'&site=cnalichn&s=11&charset=gbk" ><span><img border="0" src="http://amos.alicdn.com/online.aw?v=2&uid='.$kf_no.'&site=cnalichn&s=11&charset=UTF-8"/></span>'.$kf_name.'</a>', //阿里旺旺代码
	  );
	}

	$kf_ol = $kf_ol[$kf_type];

    return $kf_ol;     
}