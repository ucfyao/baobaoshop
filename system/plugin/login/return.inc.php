<?php
if (!defined('IN_PLUGIN')) {
    exit('Access Denied');
}
$account_type = array('alipay' => '支付宝', 'qq' => 'qq', 'sina' => '新浪微博', 'wechat' => '微信');

$method = '_login';
require_cache(PLUGIN_PATH . PLUGIN_ID . '/library/login_factory.class.php');
$login_factory = new login_factory($_GET['login_code']);
$user_info = $login_factory->$method();
$_GET['openid'] = $user_info['unionid'] ? $user_info['unionid'] : $user_info['openid'];
$_GET['account_name'] = $user_info['username'];
$_GET['avatar'] = $user_info['avatar'];
$_GET['account_type'] = $account_type[$_GET['login_code']];
unset($_GET['code']);
if (!$_GET['openid']) {
    $message = lang('login_bind_error', 'login#language');
    exit(include TPL_PATH . 'tip.tpl');
}
$_GET['type'] = $_GET['login_code'];
$minfo = model('member/member', 'service')->init();
if ($minfo['id'] > 0) {
    $result = model('#login#member_oauth', 'service')->check_user($_GET['openid'], $_GET['login_code']);
    if (!$result) {
        $data = array();
        $data['member_id'] = $minfo['id'];
        $data['openid'] = $_GET['openid'];
        $data['type'] = $_GET['type'];
        $data['dateline'] = TIMESTAMP;
        $data['account_name'] = $user_info['username'] ? $user_info['username'] : '';
        $bind_result = model('#login#member_oauth')->add($data);
        if (!$bind_result) {
            $message = lang('login_bind_error', 'login#language');
            exit(include TPL_PATH . 'tip.tpl');
        }
        redirect(__ROOT__ . 'plugin.php?id=login:third_login_bind');
    } else {
        $message = lang('login_bind_alread', 'login#language');
        exit(include TPL_PATH . 'tip.tpl');
    }
} else {
    if ($_GET['unionid']) $_GET['openid'] = $_GET['unionid'];
    $result = model('#login#member_oauth', 'service')->check_user($_GET['openid'], $_GET['login_code']);
    if (!$result) {
        $config = cache('login', '', 'plugin');
        if ($config[$_GET['type']]['quick']) login_without_reg($_GET);
        // 未绑定
        $SEO = seo('第三方登录账号绑定');
        if (defined('MOBILE')) {
            include PLUGIN_PATH . PLUGIN_ID . '/template/wap/return.html';
        } else {
            include PLUGIN_PATH . PLUGIN_ID . '/template/pc/return.html';
        }
    } else {
        $userInfo = model('member/member')->find($result['member_id']);
        if (!$userInfo) {
            $message = lang('member_not_exist', 'login#language');
            exit(include TPL_PATH . 'tip.tpl');
        }
        switch ($userInfo['status']) {
            case '-1':
                $message = lang('id_not_exist', 'login#language');
                exit(include TPL_PATH . 'tip.tpl');
                break;
            case '0':
                $message = lang('id_not_confirm', 'login#language');
                exit(include TPL_PATH . 'tip.tpl');
                break;
            case '2':
                $message = lang('id_disable', 'login#language');
                exit(include TPL_PATH . 'tip.tpl');
                break;
            default:
                //登录
                dologin($userInfo['id'], $userInfo['password']);
                login_inc($userInfo['id']);
                runhook('after_login',$userInfo);
                redirect(__ROOT__.urldecode(cookie('url_forward')));
                break;
        }
    }
}


/**
 * 免注册登录第三方账号
 * @param $info
 */
function login_without_reg($info)
{
    if (model('#login#member_oauth')->where("openid = {$info['openid']}")->find()) return;

    require_cache(PLUGIN_PATH . PLUGIN_ID . '/function/function.php');
    $name = strtoupper(substr($info['type'], 0, 2));
    // 会员信息添加
    $member['username'] = $name . uniqid();
    $member['encrypt'] = random(6);
    $member['password'] = md5(md5($member['password']) . $member['encrypt']);
    $member['group_id'] = 1;
    $member['islock'] = 0;
    $member['register_time'] = time();
    $member['register_ip'] = get_client_ip();
    $member_id = model('member')->add($member);

    // 认证信息添加
    $oauth['member_id'] = $member_id;
    $oauth['openid'] = $info['openid'];
    $oauth['type'] = $info['type'];
    $oauth['dateline'] = TIMESTAMP;
    $oauth['account_name'] = $member['username'] ? $member['username'] : '';
    $oauth_id = model('#login#member_oauth')->add($oauth);

    dologin($member_id, $member['password']);
    $member['id'] = $member_id;
    runhook('after_login',$member);
    showmessage(lang('login_bind_success', 'login#language'), urldecode(__ROOT__.cookie('url_forward')), 1);
}