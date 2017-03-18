<?php
if(!defined('IN_ADMIN')) {
    exit('Access Denied');
}
$logins = cache('login','','plugin');
$logins[$_GET['code']]['enabled'] = 1 - $logins[$_GET['code']]['enabled'];
cache('login',$logins,'plugin');
showmessage(lang('change_success','login#language'),'',1);