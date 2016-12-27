<?php
/**
 * 		发货单日志服务层
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */
class order_parcel_log_service extends service {

	public function _initialize() {
		$this->table = $this->load->table('order/order_parcel_log');
	}

	public function add($params){
		return $this->table->update($params);
	}
}