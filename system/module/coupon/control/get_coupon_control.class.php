<?php
class get_coupon_control extends cp_control {
    public function _initialize() {
		parent::_initialize();
		if($this->member['id'] < 1) {
			redirect(url('cp/index'));
		}
        $this->service = $this->load->service('coupon/coupon_list');
        $this->coupon_service = $this->load->service('coupon/coupon');
	}

	/**
	 * [index 会员优惠券]
	 */
	public function index(){
		$mid = $this->member['id'];
		$coupons = $this->service->fetch_by_id($mid,$_GET['status']);
		$this->load->librarys('View')->assign('coupons',$coupons)->display('coupon');
	}

	/**
	 * [cart_coupon 订单页面优惠券]
	 */
	public function cart_coupon(){
		$mid = (int)$this->member['id'];
		$skuids = $_GET['skuids'];
		$status = (int)$_GET['status'] ? $_GET['status'] : 1;
		$num = isset($_GET['real_amount']) ? $_GET['real_amount'] : 0;
		$coupons = $this->service->fetch_by_id($mid,$status,$skuids,$num);
		$this->load->librarys('View')->assign('skuids',$skuids)->assign('coupons',$coupons)->assign('num',$num)->display('settlement_coupon');
	}

	/**
	 * [receive 领取优惠券]
	 */
	public function receive() {
		$coupon_id = (int)$_GET['id'];
		$mid = (int)$this->member['id'];
		$result = $this->service->create_card($coupon_id, 1, 8, 1, $mid);
		if($result === FALSE) {
			showmessage($this->service->error);
		} else {
			showmessage(lang('receive_coupon_success','coupon/language'), '',1,'', 'json');
		}
	}

}
