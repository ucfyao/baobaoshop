<?php 
/**
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */
defined('IN_PLUGIN') OR define('IN_PLUGIN', TRUE);
hd_core::load_class('init', 'goods');
class index_control extends init_control {
	public function _initialize() {
		parent::_initialize();
		$id = $_GET['id'];
		list($id, $module) = explode(':', $id);
		if (empty($id)) showmessage('参数错误');
		$plugins = cache('plugins');
		$appvars = cache('appvars');
		$plugin = $plugins[$id];
		$pluginvar = $pluginvars[$id];
		if(!in_array('plugin.'.$id, array_keys($plugins))) {
			showmessage('插件不存在或未开启');
		}		
		$module = !$module ? $id : $module;
		define('PLUGIN_ID', $id);
		define('PLUGIN_MODULE', $module);
		$libfile = PLUGIN_PATH.$id.DIRECTORY_SEPARATOR.$module.'.inc.php';
		if (!file_exists($libfile)) die('访问模块不存在');
		include $libfile;
	}

	/* 强制退出 */
	public function _empty() {
		return FALSE;
	}
}