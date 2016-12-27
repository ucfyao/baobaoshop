<?php
/**
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */
class admin_menu_service extends service
{
	protected $sqlmap = array();

	public function _initialize() {
		$this->model = $this->load->table('admin/admin_menu');
	}
	
	public function fetch_all_by_admin_id($admin_id = 0) {
		return $this->model->where(array('admin_id' => $admin_id))->select();
	}
}