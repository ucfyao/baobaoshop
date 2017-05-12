<?php
if(!defined('IN_PLUGIN')) {
  exit('Access Denied');
}

require_cache(PLUGIN_PATH . PLUGIN_ID . '/function/alidayu/function.php'); //接入公共参数

$member = model('member/member','service')->init();

$config = cache('hsb_wcash_config', '', 'plugin');

$config['rate_j'] = 1 - $config['rate']/100;

if($this->member['id'] < 1) {
			$url_forward = $_GET['url_forward'] ? $_GET['url_forward'] : urlencode($_SERVER['REQUEST_URI']);
			showmessage(lang('_not_login_'),url('member/public/login',array('url_forward' => $url_forward)),0);
		}


$openids = model('member_oauth')->where(array('member_id' => $member['id']))->getField('openid', true);
$openid = $openids[0];

$act = $_GET['act'];

if($act=='list'){
	
	$ajax = $_GET['ajax'];
	
  if($ajax=='list'){
	
	$limit  = (isset($_GET['limit'])) ? $_GET['limit'] : 10;
	$logs = $this->load->table('hsb_wcash_log')->where(array('member_id'=> $this->member['id']))->page($_GET['page'])->order('up_date DESC')->limit($limit)->select();
	$data['logs'] = array();

	foreach ($logs as $log) {
	  $log['up_date'] = date('Y-m-d H:i:s',$log['up_date']);
  
	if($log['do_date'] !== '0'){
	  $log['do_date'] = date('Y-m-d H:i:s',$log['do_date']);
	}else{
	  $log['do_date'] = '';
	}
  
	if($log['account_type'] == 'alipay'){
	  $log['account_type'] = '支付宝';
	}elseif($log['account_type'] == 'wxpay'){
	  $log['account_type'] = '微信';
	  $log['account'] = '/';
	}
  
	if($log['status'] == '0'){
	  $log['status'] = '未处理';
	}elseif($log['status'] == '-1'){
	  $log['status'] = '已拒绝';
	}else{
	  $log['status'] = '已打款';
	}

	$data['logs'][] = $log;
	}

	$data['count']  = $this->load->table('hsb_wcash_log')->where($sqlmap)->count();
	$data['pages']  = pages($count,$limit);

	$this->load->librarys('View')->assign('data',$data);
	$data = $this->load->librarys('View')->get('data');
	echo json_encode($data);

  }else{
	
    $SEO = seo('提现记录' ,''); 
	
    if(defined('MOBILE')){
    $limit  = (isset($_GET['limit'])) ? $_GET['limit'] : 5;
    }else{	
    $limit  = (isset($_GET['limit'])) ? $_GET['limit'] : 10;
    }
		  
    $logs = model('hsb_wcash_log')->where(array('member_id'=> $this->member['id']))->order('up_date DESC')->page($_GET['page'])->limit($limit)->select();
    $lists = array();
    foreach ($logs as $key => $log) {
      $lists[] = $log;
    }

    $count = $this->load->table('hsb_wcash_log')->where(array('userid'=> $this->member['id']))->count();
    $pages = pages($count,$limit);
	
    if(defined('MOBILE')){
      include PLUGIN_PATH.PLUGIN_ID.'/template/list_wap.html';
    }else{
      include PLUGIN_PATH.PLUGIN_ID.'/template/list_pc.html';
    }

  }
  
}elseif($act=='checkmobile'){

    if(!$member['mobile']){
	
	 $json='{"status":"0","message":"您还未绑定手机！"}';
	 
	}else{
      $sqlmap = array();
      $sqlmap['mobile'] = $member['mobile'];
      $sqlmap['action'] = remove_xss($_GET['action']);

      $get_vcode = model('vcode')->where($sqlmap)->find();
	  
	  $vcode = random(4,1);
	
	if($get_vcode){
	
	  $data = array();
      $data['vcode'] = $vcode;
      $data['mobile'] = $member['mobile'];
      $data['mid'] = $member['id'];
      $data['action'] = remove_xss($_GET['action']);
      $data['dateline'] = TIMESTAMP;

      $result_vcode = model('vcode')->where(array('id' => $get_vcode['id']))->save($data);
	
	}else{
	
	  $data = array();
      $data['vcode'] = $vcode;
      $data['mobile'] = $member['mobile'];
      $data['mid'] = $member['id'];
      $data['action'] = remove_xss($_GET['action']);
      $data['dateline'] = TIMESTAMP;

      $result_vcode = model('vcode')->update($data);
	
	}

	$senddata = array(
    'mobile' => $member['mobile'],
	'code' => $vcode,	   
    );
	   
    $result = SendSms($senddata);
	
	 if (!$result) $json='{"status":"0","message":"验证码发送失败！"}';
	  $json='{"status":"1","message":"验证码发送成功！"}';
	  
	}
	echo $json;

}else{
	
	if (IS_POST){
	  
	  if (remove_xss($_GET['money']) > $member['money']) showmessage('您的余额不足！');
	  
	  if (remove_xss($_GET['money']) < $config['lessmoney']) showmessage('单次提现不得少于'.$config['lessmoney'].'元！');
	  
	  if($config['mobilecheck_status']){
	    $sqlmap = array();
        $sqlmap['mobile'] = $member['mobile'];
        $sqlmap['action'] = 'wcash_checkmobile';
		$sqlmap['vcode'] = remove_xss($_GET['vcode']);
        $get_vcode = model('vcode')->where($sqlmap)->find();
		if(!$get_vcode) showmessage('验证码不正确！');  
	  }
	  
	  //写入提现记录表
	  $out_biz_no = date('YmdHis').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 6);
	  
	  $data = array();
	  $data['account'] = remove_xss($_GET['account']);
	  $data['account_type'] = remove_xss($_GET['account_type']);
	  $data['true_name'] = remove_xss($_GET['true_name']);
	  $data['money'] = remove_xss($_GET['money']);
	  $data['rate'] = $config['rate'];
	  $data['go_money'] = sprintf("%.2f",remove_xss($_GET['money']) * $config['rate_j']); ;
	  $data['member_id'] = $member['id'];
	  $data['up_date'] = TIMESTAMP;
	  $data['out_biz_no'] = $out_biz_no;
	  $result_add = model('hsb_wcash_log')->add($data);
	  

	  //更新会员账户余额
	  $this->member = model('member')->where(array('id' => $member['id']))->find();	
	  $money = $this->member['money'];	
	  $frozen_money = $this->member['frozen_money'];	
	  $money_detail = sprintf("%.2f",$money - remove_xss($_GET['money']));	
	  $frozen_money_detail = sprintf("%.2f",$frozen_money + remove_xss($_GET['money']));	
	  $member_data = array();
	  $member_data['money'] = $money_detail;	
	  $member_data['frozen_money'] = $frozen_money_detail;
	  model('member')->where(array('id' => $this->member['id']))->save($member_data);
	
	  //更新余额变动记录
	  $member_log_data['mid'] = $member['id'];
	  $member_log_data['value'] = '-'.remove_xss($_GET['money']);
	  $member_log_data['msg'] = '申请提现，冻结余额增加'.remove_xss($_GET['money']).'元';
	  $member_log_data['dateline'] = $data['up_date'];
	  $member_log_data['type'] = 'money';
	  $member_log_data['money_detail'] = '{"money":"'.$money_detail.'"}';	
	  model('member_log')->add($member_log_data);
	  
	  //删除验证码
	  $sqlmap = array();
      $sqlmap['mobile'] = $member['mobile'];
      $sqlmap['action'] = 'wcash_checkmobile';

      $get_vcode = model('vcode')->where($sqlmap)->delete();
	  
	  
	  showmessage(lang('post_message_success', 'hsb_wcash#language'), url('plugin/index/index',array('id' => 'hsb_wcash:index','act'=>$act)), 1);
	
	}else{ //IS_POST
	
	  if(!$config['alipay_status'] && !$config['wxpay_status']){
	  
	  showmessage('', url('plugin/index/index',array('id' => 'hsb_wcash:index','act'=>'list')), 1);
	  
	  }
	  
	  $SEO = seo('余额提现' ,'');
	  
	  if($act=='wxpay'){
	  
	    if(!$config['wxpay_status']){
	     showmessage('', url('plugin/index/index',array('id' => 'hsb_wcash:index')), 1);
	    }

	    if(defined('MOBILE')){
	      include PLUGIN_PATH.PLUGIN_ID.'/template/index_wap.html';
	    }else{
		  include PLUGIN_PATH.PLUGIN_ID.'/template/index_pc.html';
	    }

	  }else{
	  
	    if(!$config['alipay_status']){
	     showmessage('', url('plugin/index/index',array('id' => 'hsb_wcash:index','act'=>'wxpay')), 1);
	    }
	  
	    if(defined('MOBILE')){
	      include PLUGIN_PATH.PLUGIN_ID.'/template/index_wap.html';
	    }else{
		  include PLUGIN_PATH.PLUGIN_ID.'/template/index_pc.html';
	    }
 
	  }
	
	}//IS_POST END

}//ACT END