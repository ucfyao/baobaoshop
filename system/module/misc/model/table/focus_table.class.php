<?php
/**
 *	    友情链接数据层
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */

class focus_table extends table {
    protected $_validate = array(
        array('title','require','{misc/title_require}',0),
		array('sort','number','{misc/sort_require}',2),
    );
}