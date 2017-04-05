<?php
class index_control extends control {
	public function _initialize() {
		parent::_initialize();
		$this->service = $this->load->service('pay/payment');
		$this->service_payment = $this->load->service('pay/payment');
	}

	public function dnotify() {
		if(!defined('_PAYMENT_')) {
			showmessage(lang('_error_action_'));
		}
		$method = _PAYMENT_;
		$ret = $this->service->_notify(_PAYMENT_);
		runhook('pay_success',$ret);
	}

	public function dreturn() {
		if(!defined('_PAYMENT_')) {
			showmessage(lang('_error_action_'));
		}
		$method = _PAYMENT_;
		$ret = $this->service->_notify(_PAYMENT_);
		runhook('pay_success',$ret);
	}

	public function ajax_check() {
		$order_sn = $_GET['order_sn'];
		if(empty($order_sn)) {
			showmessage(lang('order_sn_not_null','order/language'));
		}
		if($order_sn[0] == 'm') {
			$_map = array();
			$_map['order_sn'] = $order_sn;
			$_map['order_status'] = 1;
			$result = $this->load->table('member_deposit')->where($_map)->find();
		} else {
			$_map = array();
			$_map['sn'] = $order_sn;
			$_map['pay_status'] = 1;
			$result = $this->load->table('order')->where($_map)->find();
		}
		if($result) {
			showmessage(lang('order_pay_success','pay/language'), url('index'), 1);
		}
	}
	
	public function wechat($order_sn,$pay_code) {
		$member = $this->load->service('member/member')->init();
		$pay_info = array();
		if($_GET['order_sn'][0] == 'm'){
			$m_order = $this->load->table('member/member_deposit')->where(array('order_sn' => $_GET['order_sn']))->find();
			if (!$m_order || $member['id'] != $m_order['mid']) {
				showmessage(lang('no_promission_view','order/language'));
			}
			if ($order['trade_status'] == 1) {
				showmessage(lang('order_paid','pay/language'));
			}
			$pay_info['trade_sn']  = $m_order['order_sn'];
			$pay_info['total_fee'] = $m_order['money'];
			$pay_info['subject']   = '订单号：'.$m_order['order_sn'];
			$pay_info['pay_bank']  = '';
			//回调链接
			$success_url = url('member/money/success',array('order_sn'=>$pay_info['trade_sn']));
			$error_url = url('member/money/pay');
		}else{
			$order = $this->load->table('order/order_trade')->where(array('trade_no' => $_GET['trade_no']))->find();
			$order_info = $this->load->table('order/order')->where(array('sn' => $order['order_sn']))->find();
			if (!$order || $member['id'] != $order_info['buyer_id']) {
				showmessage(lang('no_promission_view','order/language'));
			}
			if ($order_info['pay_status'] == 1) {
				showmessage(lang('order_paid','pay/language'));
			}
			/* 发起wechat支付请求 */
			$pay_info['trade_sn']  = $order['trade_no'];
			$pay_info['total_fee'] = $order['total_fee'];
			$pay_info['subject']   = '订单号：'.$order['trade_no'];
			$pay_info['pay_bank']  = '';
			//回调链接
			$success_url = url('order/order/pay_success',array('order_sn'=>$order['order_sn']));
			$error_url = url('order/order/detail',array('order_sn'=>$order['order_sn']));
		}
		/* 请求支付 */
		$gateway = $this->service_payment->gateway($_GET['pay_code'],$pay_info);
		if($gateway === false) showmessage(lang('pay_set_error','pay/language'));
		include template('wechat_js');
	}
}