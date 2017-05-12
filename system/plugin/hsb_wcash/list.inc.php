<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

if(!defined('IN_ADMIN')) {
    exit('Access Denied');
}
require_cache(PLUGIN_PATH . PLUGIN_ID . '/function/function.php'); //接入公共参数

$config = cache('hsb_wcash_config', '', 'plugin');

$act = $_GET['act'];

if($act=="alipay_autopay"){
      
      require_cache(PLUGIN_PATH . PLUGIN_ID . '/function/alipay/function.php'); //接入公共参数(支付宝）

      if($config['alipay_autopay_status']){

      $paydata = array(
      'id' => $_GET['id'],   
      );
	   
      $result = alipay_autopay($paydata);

      if(!empty($result) && $result['code']=='10000'){
	
      $get_wcash = model('hsb_wcash_log')->where(array('out_biz_no' => $result['out_biz_no']))->find();

	  //写入提现记录表
	  $wcash_data = array();
	  $wcash_data['status'] = '1';	
	  $wcash_data['order_id'] = $result['order_id'];
	  $wcash_data['pay_date'] = $result['pay_date'];
	  $wcash_data['do_date'] = TIMESTAMP;
	  model('hsb_wcash_log')->where(array('id' => $get_wcash['id']))->save($wcash_data);

	  //更新会员账户余额
	  $this->member = model('member')->where(array('id' => $get_wcash['member_id']))->find();	
	  $frozen_money = $this->member['frozen_money'];	
	  $frozen_money_detail = sprintf("%.2f",$frozen_money - $get_wcash['money']);	
	  $member_data = array();
	  $member_data['frozen_money'] = $frozen_money_detail;
	  model('member')->where(array('id' => $this->member['id']))->save($member_data);
	
	  //更新余额变动记录
	  $member_log_data['mid'] = $get_wcash['member_id'];
	  $member_log_data['value'] = '0';
	  $member_log_data['msg'] = '提现申请已处理，相应款项已支付，冻结余额减少'.$get_wcash['money'].'元，请查收您的收款账户';
	  $member_log_data['dateline'] = TIMESTAMP;
	  $member_log_data['type'] = 'money';
	  $member_log_data['money_detail'] = '{"money":"'.$this->member['money'].'"}';	
	  model('member_log')->add($member_log_data);
		
      } //$result['code']=='10000' end
	  
	   if(!empty($result) && $result['code']=='10000'){
	   $message = '实时支付成功';
	   }else{
	   $message = $result['sub_msg'];
	   }
	  
	   showmessage($message, url('admin/app/module',array('mod' => 'hsb_wcash:list')), 1);
	  
	  } //$config['autopay_status'] end

}elseif($act=="wxpay_autopay"){
      
      require_cache(PLUGIN_PATH . PLUGIN_ID . '/function/wxpay/function.php'); //接入公共参数(支付宝）

      if($config['wxpay_autopay_status']){

      $paydata = array(
      'id' => $_GET['id'],   
      );
	   
      $result = wxpay_autopay($paydata);
		
      if($result['return_code']=='SUCCESS' && $result['result_code']=='SUCCESS'){
		
		
      $get_wcash = model('hsb_wcash_log')->where(array('out_biz_no' => $result['partner_trade_no']))->find();

	  //写入提现记录表
	  $wcash_data = array();
	  $wcash_data['status'] = '1';	
	  $wcash_data['order_id'] = $result['payment_no'];
	  $wcash_data['pay_date'] = $result['payment_time'];
	  $wcash_data['do_date'] = TIMESTAMP;
	  model('hsb_wcash_log')->where(array('id' => $get_wcash['id']))->save($wcash_data);

	  //更新会员账户余额
	  $this->member = model('member')->where(array('id' => $get_wcash['member_id']))->find();	
	  $frozen_money = $this->member['frozen_money'];	
	  $frozen_money_detail = sprintf("%.2f",$frozen_money - $get_wcash['money']);	
	  $member_data = array();
	  $member_data['frozen_money'] = $frozen_money_detail;
	  model('member')->where(array('id' => $this->member['id']))->save($member_data);
	
	  //更新余额变动记录
	  $member_log_data['mid'] = $get_wcash['member_id'];
	  $member_log_data['value'] = '0';
	  $member_log_data['msg'] = '提现申请已处理，相应款项已支付，冻结余额减少'.$get_wcash['money'].'元，请查收您的收款账户';
	  $member_log_data['dateline'] = TIMESTAMP;
	  $member_log_data['type'] = 'money';
	  $member_log_data['money_detail'] = '{"money":"'.$this->member['money'].'"}';	
	  model('member_log')->add($member_log_data);
		
      } //$result['return_code']=='SUCCESS' && $result['result_code']=='SUCCESS' end
	  
	   if($result['return_code']=='SUCCESS' && $result['result_code']=='SUCCESS'){
	   $message = '实时支付成功';
	   }else{
	   $message = $result['err_code_des'];
	   }
	   
	   showmessage($message, url('admin/app/module',array('mod' => 'hsb_wcash:list')), 1);
	  
	  } //$config['wxpay_autopay_status'] end

}elseif($act=="pay"){

      $get_wcash = model('hsb_wcash_log')->where(array('id' => $_GET['id']))->find();

	  //写入提现记录表
	  $wcash_data = array();
	  $wcash_data['status'] = '2';	
	  $wcash_data['do_date'] = TIMESTAMP;
	  model('hsb_wcash_log')->where(array('id' => $_GET['id']))->save($wcash_data);

	  //更新会员账户余额
	  $this->member = model('member')->where(array('id' => $get_wcash['member_id']))->find();	
	  $frozen_money = $this->member['frozen_money'];	
	  $frozen_money_detail = sprintf("%.2f",$frozen_money - $get_wcash['money']);	
	  $member_data = array();
	  $member_data['frozen_money'] = $frozen_money_detail;
	  model('member')->where(array('id' => $this->member['id']))->save($member_data);
	
	  //更新余额变动记录
	  $member_log_data['mid'] = $get_wcash['member_id'];
	  $member_log_data['value'] = '0';
	  $member_log_data['msg'] = '提现申请已处理，相应款项已支付，冻结余额减少'.$get_wcash['money'].'元，请查收您的收款账户';
	  $member_log_data['dateline'] = TIMESTAMP;
	  $member_log_data['type'] = 'money';
	  $member_log_data['money_detail'] = '{"money":"'.$this->member['money'].'"}';	
	  model('member_log')->add($member_log_data);

      showmessage(lang('post_message_success', 'hsb_wcash#language'), url('admin/app/module',array('mod' => 'hsb_wcash:list')), 1);

}elseif($act=="nopay"){

      $get_wcash = model('hsb_wcash_log')->where(array('id' => $_GET['id']))->find();
	  
     
	  //写入提现记录表
	  $wcash_data = array();
	  $wcash_data['status'] = '-1';	
	  $wcash_data['do_date'] = TIMESTAMP;
	  model('hsb_wcash_log')->where(array('id' => $_GET['id']))->save($wcash_data);

	  //更新会员账户余额
	  $this->member = model('member')->where(array('id' => $get_wcash['member_id']))->find();	
	  $money = $this->member['money'];	
	  $frozen_money = $this->member['frozen_money'];	
	  $money_detail = sprintf("%.2f",$money + $get_wcash['money']);	
	  $frozen_money_detail = sprintf("%.2f",$frozen_money - $get_wcash['money']);	
	  $member_data = array();
	  $member_data['money'] = $money_detail;	
	  $member_data['frozen_money'] = $frozen_money_detail;
	  model('member')->where(array('id' => $this->member['id']))->save($member_data);
	
	  //更新余额变动记录
	  $member_log_data['mid'] = $get_wcash['member_id'];
	  $member_log_data['value'] = $get_wcash['money'];
	  $member_log_data['msg'] = '提现申请被拒绝，冻结余额减少'.$get_wcash['money'].'元，可用余额增加'.$get_wcash['money'].'元';
	  $member_log_data['dateline'] = TIMESTAMP;
	  $member_log_data['type'] = 'money';
	  $member_log_data['money_detail'] = '{"money":"'.$money_detail.'"}';	
	  model('member_log')->add($member_log_data);
	  
	  showmessage(lang('post_message_success', 'hsb_wcash#language'), url('admin/app/module',array('mod' => 'hsb_wcash:list')), 1);

}elseif($act=="repay"){
      
	  $get_wcash = model('hsb_wcash_log')->where(array('id' => $_GET['id']))->find();

	  //写入提现记录表
	  $wcash_data = array();
	  $wcash_data['status'] = '0';	
	  $wcash_data['do_date'] = TIMESTAMP;
	  model('hsb_wcash_log')->where(array('id' => $_GET['id']))->save($wcash_data);

	  //更新会员账户余额
	  $this->member = model('member')->where(array('id' => $get_wcash['member_id']))->find();	
	  $frozen_money = $this->member['frozen_money'];	
	  $frozen_money_detail = sprintf("%.2f",$frozen_money + $get_wcash['money']);	
	  $member_data = array();
	  $member_data['frozen_money'] = $frozen_money_detail;
	  model('member')->where(array('id' => $this->member['id']))->save($member_data);
	
	  //更新余额变动记录
	  $member_log_data['mid'] = $get_wcash['member_id'];
	  $member_log_data['value'] = '0';
	  $member_log_data['msg'] = '提现申请已处理并已付款，但支付款项被退回，冻结余额增加'.$get_wcash['money'].'元';
	  $member_log_data['dateline'] = TIMESTAMP;
	  $member_log_data['type'] = 'money';
	  $member_log_data['money_detail'] = '{"money":"'.$this->member['money'].'"}';	
	  model('member_log')->add($member_log_data);

      showmessage(lang('post_message_success', 'hsb_wcash#language'), url('admin/app/module',array('mod' => 'hsb_wcash:list')), 1);

}else{
	
$sqlmap = array();

if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {

 $sqlmap['true_name'] = array('LIKE','%'.$_GET['keyword'].'%');

}

$infos = model('hsb_wcash_log')->page($_GET['page'])->limit($limit)->order('up_date DESC')->where($sqlmap)->select();
$lists = array();
foreach ($infos as $key => $info) {
      $lists[] = $info;
}

$limit = (isset($_GET['limit']) && is_numeric($_GET['limit'])) ? $_GET['limit'] : 20;
$count = $this->load->table('hsb_wcash_log')->where($sqlmap)->count();
$pages = $this->admin_pages($count, $limit);

include(PLUGIN_PATH . PLUGIN_ID . '/template/list.php');

}