<?php
/**
 *	    文章分类数据层
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */

class article_category_table extends table {
    protected $_validate = array(
        array('name','require','{misc/classkit_name_require}',0),
		array('parent_id','number','{misc/parent_class_not_exist}',2),
		array('sort','number','{misc/sort_require}',2),
    );
    protected $_auto = array(
    	
    ); 
}