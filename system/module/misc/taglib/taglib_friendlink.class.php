<?php
/**
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */
class taglib_friendlink
{
	public function __construct() {
		$this->model = model('misc/friendlink');
	}
	public function lists($sqlmap = array(), $options = array()) {
		$this->model->where($this->build_map($sqlmap));
		if($options['limit']){
			$this->model->limit($options['limit']);
		}
		if($sqlmap['order']){
			$this->model->order($sqlmap['order']);
		}
		return  $this->model->select();
	}
	public function build_map($data){
		$sqlmap = array();
		$sqlmap['display'] = 1;
		if($data['_string']){
			$sqlmap['_string'] = $data['_string'];
		}
		return $sqlmap;
	}
}