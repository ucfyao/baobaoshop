<?php
/**
 *		捆绑营销数据层
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */

class promotion_group_table extends table {
	protected $_validate = array(
        array('title','require','{promotion/title_require}',table::MUST_VALIDATE),
        array('subtitle','require','{promotion/subtitle_require}',table::MUST_VALIDATE),
    );
}