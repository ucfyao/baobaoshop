<?php
/**
 * 		订单支付模型
 *      [Haidao] (C)2013-2099 Dmibox Science and technology co., LTD.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.haidao.la
 *      tel:400-600-2042
 */
class order_payment_table extends table {

    // 自动验证
	protected $_validate = array(
        // 订单号唯一
        array('order_sn', '', '{order/order_sn_already_exist}', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),

        // 订单支付号唯一
        array('pay_sn', '', '{order/order_sn_already_exist}', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),

        // 支付金额为数字
        array('pay_sn', 'number', '{order/order_payment_money_error}', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    // 自动完成
    protected $_auto = array(
        // 支付时间
        array('pay_time','time',3,'function')
    );
    
}