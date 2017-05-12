<?php
if (!defined('IN_PLUGIN')) {
    exit('Access Denied');
}
if (IS_POST) {
    require_cache(PLUGIN_PATH . PLUGIN_ID . '/function/function.php');
    extract($_GET);
    if (empty($type)) showmessage(lang('login_type_error', 'login#language'));
    if (empty($openid)) showmessage(lang('login_member_error', 'login#language'));
    $sqlmap = array();
    $sqlmap['username'] = $username;
    $userInfo = model('member/member')->where($sqlmap)->find();
    if (!$userInfo || md5(md5($password) . $userInfo['encrypt']) != $userInfo['password']) {
        showmessage(lang('username_password_error', 'login#language'), '', 0);
    } else {
        switch ($userinfo['status']) {
            case '-1':
                showmessage(lang('id_not_exist', 'login#language'), '', 0);
                break;
            case '0':
                showmessage(lang('id_not_confirm', 'login#language'), '', 0);
                break;
            case '2':
                showmessage(lang('id_disable', 'login#language'), '', 0);
                break;
            default:
                // 绑定第三方账号
                $result = model('#login#member_oauth', 'service')->check_bind($userInfo['id'], $type);
                if ($result) {
                    showmessage(lang('login_bind_alread', 'login#language'), '', 0);
                }
                $data = array();
                $data['member_id'] = $userInfo['id'];
                $data['openid'] = $openid;
                $data['type'] = $type;
                $data['dateline'] = TIMESTAMP;
                $data['account_name'] = $account_name;
                $_result = model('#login#member_oauth')->add($data);
                if (!$_result) showmessage(lang('login_bind_error', 'login#language'), '', 0);
                dologin($userInfo['id'], $userInfo['password']);
                runhook('after_login',$userInfo);
                showmessage(lang('login_bind_success', 'login#language'), urldecode(cookie('url_forward')), 1);
                break;
        }
    }
} else {
    if (defined('MOBILE')) {
        include PLUGIN_PATH . PLUGIN_ID . '/template/wap/login_bind.html';
    } else {
        include PLUGIN_PATH . PLUGIN_ID . '/template/pc/login_bind.html';
    }
}