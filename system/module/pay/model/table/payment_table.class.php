<?php
/**
 *		支付方式数据层
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */

class payment_table extends table {
	protected $_validate = array(
        array('pay_code','require','{pay/pay_code_require}',table::MUST_VALIDATE),
        array('pay_name','require','{pay/pay_name_require}',table::MUST_VALIDATE),
        array('config','require','{pay/config_require}',table::MUST_VALIDATE),
    );
    protected $_auto = array(
    	array('dateline','time',2,'function'),
    );
}