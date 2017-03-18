<?php
hd_core::load_class('init', 'goods');
class public_control extends init_control {
    public function _initialize() {
		parent::_initialize();
        $this->service = $this->load->service('coupon/coupon_list');
	}

	/**
	 * [remind 优惠券提醒]
	 */
	public function remind(){
		$this->service->remind_coupons();
	}

}
