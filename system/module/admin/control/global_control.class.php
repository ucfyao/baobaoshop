<?php
/**
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */
class global_control extends init_control
{
	public function _initialize() {
		parent::_initialize();

	}

    /* 管理登录 */
	public function index() {
		$this->load->librarys('View')->display('global_index');
    }
}