<?php
/**
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */
class member_deposit_service extends service
{
	protected $sqlmap = array();

	public function _initialize(){
		$this->table = $this->load->table('member_deposit');
	}

	public function wlog($data = array(),$sqlmap = array()) {
		if(!empty($sqlmap) && $sqlmap !== TRUE){
			$r = $this->table->where($sqlmap)->save($data);
		}else{
			$r = $this->table->add($data);
		}
		return $r;
	}
	public function is_sucess($sqlmap){
		return  $this->table->where($sqlmap)->order('id DESC')->getField('order_status');
	}
}