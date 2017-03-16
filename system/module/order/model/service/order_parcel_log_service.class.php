<?php
/**
 * 		发货单日志服务层
 *      [Haidao] (C)2013-2099 Dmibox Science and technology co., LTD.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.haidao.la
 *      tel:400-600-2042
 */
class order_parcel_log_service extends service {

	public function _initialize() {
		$this->table = $this->load->table('order/order_parcel_log');
	}

	public function add($params){
		return $this->table->update($params);
	}
}