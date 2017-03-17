<?php
if (!defined('IN_PLUGIN')) {
    exit('Access Denied');
}
$setting = cache('setting');
if (IS_POST) {
    require_cache(PLUGIN_PATH . PLUGIN_ID . '/function/function.php');
    $result = model('member/member', 'service')->register($_GET);
    if ($result) {
        // 绑定第三方账号
        $member = model('member/member', 'service')->init();
        $data = array();
        $data['member_id'] = $member['id'];
        $data['openid'] = $_GET['openid'];
        $data['type'] = $_GET['type'];
        $data['dateline'] = TIMESTAMP;
        $data['account_name'] = $_GET['username'] ? $_GET['username'] : '';
        $result_add = model('#login#member_oauth')->add($data);
        if (!$result_add) showmessage(lang('login_bind_error', 'login#language'));
        dologin($member['id'], $_GET['password']);
        runhook('after_login',$member);
        showmessage(lang('login_bind_success', 'login#language'), urldecode(cookie('url_forward')), 1);
    } else {
        showmessage(lang('register_error', 'login#language'), '', 0);
    }
} else {
    $sms_reg = false;
    $sms_enabled = model('notify/notify')->where(array('code' => 'sms', 'enabled' => 1))->find();
    if ($sms_enabled) {
        $sqlmap['id'] = 'sms';
        $sqlmap['enabled'] = array('like', '%register_validate%');
        $sms_reg = model('notify/notify_template')->where($sqlmap)->find();
    }
    if (defined('MOBILE')) {
        include PLUGIN_PATH . PLUGIN_ID . '/template/wap/reg_bind.html';
    } else {
        include PLUGIN_PATH . PLUGIN_ID . '/template/pc/reg_bind.html';
    }
}