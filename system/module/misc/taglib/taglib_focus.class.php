<?php
/**
 *      [Haidao] (C)2013-2099 Dmibox Science and technology co., LTD.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.haidao.la
 *      tel:400-600-2042
 */
class taglib_focus
{
	public function __construct() {
		$this->service = model('misc/focus','service');
	}
	public function lists($sqlmap = array(), $options = array()) {
		$lists = $this->service->focus_lists($sqlmap,$options);
		return $lists;
	}
}