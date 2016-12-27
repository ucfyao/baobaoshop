<?php
/**
 *	    友情链接数据层
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */

class navigation_table extends table {
    protected $_validate = array(
        array('name','require','{misc/article_name_require}',0),
		array('sort','number','{misc/sort_require}',2),
    );

    protected $_auto = array(
    	
    );
}