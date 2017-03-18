<?php
hd_core::load_class('init', 'admin');
class coupon_control extends init_control {
    public function _initialize() {
        parent::_initialize();
        $this->service = model('coupon', 'service');
        $this->coupon_list_service = model('coupon_list', 'service');
	}

	/**
	 * [index 优惠券活动列表]
	 */
	public function index(){
		$limit = (isset($_GET['limit']) && is_numeric($_GET['limit'])) ? $_GET['limit'] : 20;
		$data = array();
		$data['limit'] = $limit;
		$data['page'] = $_GET['page'];
        $info = $this->service->get_lists($data);
        $count = $this->service->count();
        $pages = $this->admin_pages($count, $limit);
		include $this->admin_tpl('coupon');
	}

	/**
	 * [add 添加]
	 */
	public function add() {
		if(checksubmit('dosubmit')) {
			$result = $this->service->update($_GET);
			if($result === FALSE) {
				showmessage($this->service->error);
			}
			showmessage(lang('add_coupon_activity_success','coupon/language'), url('index'), 1);
		} else {
			include $this->admin_tpl('coupon_add');
		}
	}

	/**
	 * [edit 修改]
	 */
	public function edit() {
		$id = (int) $_GET['id'];
		$info = $this->service->fetch_by_id($_GET['id']);
		if(!$info) {
			showmessage(lang('_param_error_'));
		}
		if(checksubmit('dosubmit')) {
			$result = $this->service->update($_GET);
			if($result === FALSE) {
				showmessage($this->service->error);
			} else {
				showmessage(lang('edit_coupon_activity_success','coupon/language'), url('index'), 1);
			}
		} else {
			$info['sku_lists'] = model('goods/goods_sku', 'service')->sku_detail($info['sku_ids']);
			include $this->admin_tpl('coupon_edit');
		}
	}

	/**
	 * [delete 删除优惠券促销活动]
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
			showmessage(lang('delete_coupon_activity_success','coupon/language'), url('index'), 1);
		}
	}

	/**
	 * [wap_coupons 后台手机diy优惠券]
	 */
    public function wap_coupons(){
    	$coupon_activity = $this->service->coupon_activity();
        include $this->admin_tpl('coupon_lists_popup');
    }
    /**
     * [ 注册送礼-所有优惠卷]
     */
    public function select_coupon(){
        $_GET['limit'] = (isset($_GET['limit']) && is_numeric($_GET['limit'])) ? $_GET['limit'] : 5;
        $coupon = $this->service->ajax_get_lists($_GET);
        $pages = $this->admin_pages($coupon['count'], $_GET['limit']);
        $this->load->librarys('View')->assign('coupon',$coupon)->assign('pages',$pages)->display('ajax_list_dialog');
    }
}
