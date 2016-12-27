<?php
/**
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */
class taglib_navigation
{
	public function __construct() {
		$this->misc_service = model('misc/navigation','service');
	}
	public function lists($sqlmap = array(), $options = array()) {
		return $this->misc_service->lists($sqlmap,$options);
	}
}