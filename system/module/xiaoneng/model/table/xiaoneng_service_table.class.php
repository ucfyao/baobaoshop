<?php

/**
 *       小能配置表数据层
 *      [Haidao] (C)2013-2099 Dmibox Science and technology co., LTD.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.haidao.la
 *      tel:400-600-2042
 */

class xiaoneng_service_table extends table{
	protected function _after_find(&$result, $options) {
		$result['config'] = json_decode($result['config'],TRUE);
		return $result;
	}
}
