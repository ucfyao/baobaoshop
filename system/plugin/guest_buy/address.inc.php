<?php
if (!defined('IN_PLUGIN')) exit('Access Denied');
$config = cache('guest_buy', '', 'plugin');

if (!checksubmit('dosubmit')) {

    if (!$_GET['action']) {
        $tip = '感谢您的购买，为您专属生成账号以便查询订单信息。账号为手机号，密码为';
        if ($config['type'] == 1) $tip .= '手机号码後6位。';
        if ($config['type'] == 2) $tip .= '您填写的邮编号码。';
        if ($config['type'] == 3) $tip .= '随机生成的密码，我们将以短信形式发送到您的手机号。';
        if ($config['type'] == 4) $tip .= $config['custom'] ?: '123456';

        if (!defined('MOBILE')) {
            include template('#pc/address_add');
        } else {
            include template('#mobile/address_add');
        }
    }
} else {

    /**
     * 收货地址信息数据
     */
    if ($_GET['action'] == 'ajax_district') {
        $result = model('admin/district', 'service')->get_children($_GET['did']);

        $load = hd_load::getInstance();
        $view = $load->librarys('View');
        $view->assign('result', $result);
        $result = $view->get('result');

        echo json_encode($result);
    }

    /**
     * 会员信息注册，收货地址填写
     */
    if ($_GET['action'] == 'add') {
        if (!preg_match("/^1[34578]{1}\d{9}$/", $_GET['mobile'])) showmessage('手机号码格式错误');
        $exsit = model('member/member')->where(array('mobile' => $_GET['mobile']))->getField('id');
        if ($exsit) showmessage('您已经注册成为我们的会员过了，请登录购买哦。', url('member/public/login'), 0);
        $mid = member_register($_GET, $config);
        $arr = explode(',', $_GET['skuids']);
        $skuids[$arr[0]] = $arr[1];
        model('order/cart', 'service')->cart_add($skuids, $mid, true);
        showmessage('信息注册成功。', -1, 1);
    }
}

/**
 * 信息注册&登录
 * @return bool
 */
function member_register($info, $config)
{
    if ($config['type'] == 1) $password = substr($info['mobile'], -6);
    if ($config['type'] == 2) $password = $info['zipcode'];
    if ($config['type'] == 3) $password = random(6);
    if ($config['type'] == 4) $password = $config['custom'] ?: "123456";

    $data['username'] = $info['mobile'];
    $data['email'] = '';
    $data['mobile'] = $info['mobile'];
    $data['encrypt'] = random(6);
    $data['password'] = md5(md5($password) . $data['encrypt']);
    $data['group_id'] = 1;
    $data['mobilestatus'] = 1;
    $data['islock'] = 0;
    $result = model('member/member')->update($data);
    if (!$result) showmessage('信息注册失败，请重试。', -1, 0);

    $address['mid'] = (int)$result;
    $address['name'] = $info['name'];
    $address['mobile'] = $info['mobile'];
    $address['district_id'] = $info['district_id'];
    $address['address'] = $info['address'];
    $address['zipcode'] = $info['zipcode'];
    $address['status'] = 1;
    $address['isdefault'] = 1;
    model('member_address')->add($address);

    model('member/member', 'service')->dologin($result);

    if ($config['type'] == 3) send_sms($info['mobile'], $password);

    return $result;
}

/**
 * 发送通知短信
 */
function send_sms($mobile, $password)
{
    // 站点信息
    $load = hd_load::getInstance();
    $cloud = $load->librarys('cloud');
    $info = $cloud->get_account_info();

    $replace['password'] = $password;

    $data['identifier'] = $info['identifier'];
    $data['tpl_id'] = 246;
    $data['mobile'] = $mobile;
    $data['tpl_vars'] = $replace;
    $params = json_encode($data);
    model('notify/queue', 'service')->add('sms', 'send', $params, 0);
}