<?php
/**
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */
class member_log_service extends service {
	public function _initialize() {
         $this->model = $this->load->table('member/member_log');
	}
	public function add($params){
		runhook('member_log_add',$params);
        return $this->model->update($params);
    }
}