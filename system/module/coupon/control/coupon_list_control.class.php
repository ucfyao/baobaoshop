<?php
hd_core::load_class('init', 'admin');
class coupon_list_control extends init_control {
    public function _initialize() {
        parent::_initialize();
        $this->service = model('coupon_list','service');
        $this->coupon_service = model('coupon', 'service');
	}

	/**
	 * [index 优惠券列表]
	 */
	public function index(){
		$limit = (isset($_GET['limit']) && is_numeric($_GET['limit'])) ? $_GET['limit'] : 10;
		$data = array();
		$data['cid'] = $_GET['cid'];
    	$count = $this->service->count($data);
		$data['limit'] = $limit;
		$data['count'] = $count;
		$data['page'] = $_GET['page'];
		$info = $this->service->get_lists($data);
        $pages = $this->admin_pages($count, $limit);
		include $this->admin_tpl('coupon_list');
	}

	/**
	 * [delete 删除优惠券]
	 */
	public function delete() {
		$ids = (array) $_GET['id'];
		if(empty($ids)) {
			showmessage(lang('_param_error_'));
		}
		$result = $this->service->delete($ids);
		if($result === FALSE) {
			showmessage($this->service->error);
		} else {
			showmessage(lang('delete_coupon_success','coupon/language'), url('index',array('cid' => $_GET['cid'])), 1);
		}
	}

	/**
	 * [delete 优惠券禁用]
	 */
	public function disable() {
		$ids = (array) $_GET['id'];
		if(empty($ids)) {
			showmessage(lang('_param_error_'));
		}
		$result = $this->service->change_status($_GET);
		if($result === FALSE) {
			showmessage($this->service->error);
		} else {
			showmessage(lang('disable_coupon_success','coupon/language'), url('index',array('cid' => $_GET['cid'])), 1);
		}
	}

}
