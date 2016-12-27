<?php
/**
 *		规格模型数据层
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */

class spec_table extends table {
	protected $_validate = array(
		array('name','require','{good/goods_spec_name_require}',table::MUST_VALIDATE),
		array('name','','{goods/goods_spec_name_unique}',table::MUST_VALIDATE,'unique'),
        array('status','number','{godos/state_require}',table::EXISTS_VALIDATE,'regex',table:: MODEL_BOTH),
        array('sort','number','{goods/sort_require}',table::EXISTS_VALIDATE,'regex',table:: MODEL_BOTH),
    );
    protected $_auto = array(
    );
}