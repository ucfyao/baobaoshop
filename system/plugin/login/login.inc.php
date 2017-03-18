<?php
if(!defined('IN_ADMIN')) {
    exit('Access Denied');
}
$logins = cache('login','','plugin');
$folders = glob(PLUGIN_PATH.PLUGIN_ID.'/library/driver/*');
foreach ($folders as $key => $folder) {
    $file = $folder. DIRECTORY_SEPARATOR .'config.xml';
    if(file_exists($file)) {
        $importtxt = @implode('', file($file));
        $xmldata = xml2array($importtxt);
        $xmldata['enabled'] = $logins[$xmldata['code']]['enabled'] ? $logins[$xmldata['code']]['enabled'] : 0;
        $xmldata['login_config'] = $logins[$xmldata['code']]['config'];
        $xmls[$xmldata['code']] = $xmldata;
    }
}
$logins = $xmls;
include(PLUGIN_PATH . PLUGIN_ID . '/template/index.php');