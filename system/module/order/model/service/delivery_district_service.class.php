<?php
/**
 * 		物流地区服务层
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */
class delivery_district_service extends service {

	public function _initialize() {
		$this->table = $this->load->table('order/delivery_district');
	}

	public function import($params){
		return $this->table->update($params);
	}
}