<?php
if(!defined('IN_ADMIN')) {
    exit('Access Denied');
}
$logins = cache('login','','plugin');
$importfile = PLUGIN_PATH.PLUGIN_ID.'/library/driver/'.$_GET['code'].'/config.xml';
if(!file_exists($importfile)) {
    showmessage (lang('login_config_lose','login#language'),url('#login'));
}
$importtxt = @implode('', file($importfile));
$xmldata = xml2array($importtxt);
if (IS_POST) {
    $infoarr = array_merge_multi($xmldata, $_GET['info']);
    $xmldata = array2xml($infoarr);       
    $config = array();
    foreach($infoarr['config'] as $key => $value) {
        $config[$key] = $value['value'];
    }
    if($_GET['code'] == 'wechat_wap'){
        $config['synchro'] = $_GET['synchro'];
        $config['force'] = $_GET['force'];
    }
    $data = array($infoarr['code'] => array(
        'login_code' => $infoarr['code'],
        'enabled'    => $_GET['enabled'],
        'quick'      => $_GET['quick'],
        'login_name' => $_GET['info']['login_name'],
        'config'     => $config,
        'login_desc' => $_GET['info']['login_desc']
    ));
    $data = array_merge_multi($logins , $data);
    cache('login', $data, 'plugin');
    showmessage(lang('_operation_success_'), url('#login'),1);
} else {
	include(PLUGIN_PATH . PLUGIN_ID . '/template/config.php');
}