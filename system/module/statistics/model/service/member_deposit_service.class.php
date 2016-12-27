<?php
/**
 *      统计服务层
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */

class member_deposit_service extends service {

	public function _initialize() {
		$this->deposit_model = $this->load->table('member_deposit');
	}

	public function _query($field,$sqlmap,$group){
		return $this->deposit_model->field($field)->where($sqlmap)->group($group)->select();
	}

}
