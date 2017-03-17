<?php
/**
 *      支付模块调用工厂
 *      [Haidao] (C)2013-2099 Dmibox Science and technology co., LTD.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.haidao.la
 *      tel:400-600-2042
 */
class login_factory
{
	public function __construct($adapter_name = '', $adapter_config = array()) {
		$this->set_adapter($adapter_name, $adapter_config);
	}
	/**
	 * 构造适配器
	 * @param  $adapter_name 支付模块code
	 * @param  $adapter_config 支付模块配置
	 */
	public function set_adapter($adapter_name, $adapter_config = array()) {
		if (!is_string($adapter_name)) return false;
		else {
			if (empty($adapter_config)) {
				$logins = cache('login','','plugin');
				if (empty($logins) || empty($logins[$adapter_name]) || empty($logins[$adapter_name]['config'])) {
					die('第三方登录方式未安装或未开启');
				}
				$adapter_config = $logins[$adapter_name]['config'];
			}
			$class_name = $adapter_name;
			$class_file = PLUGIN_PATH.PLUGIN_ID.'/library'.DIRECTORY_SEPARATOR.'driver'.DIRECTORY_SEPARATOR.$class_name.DIRECTORY_SEPARATOR.$class_name.'.class.php';
			if (!file_exists($class_file)) {
				die('第三方登录接口不存在');
			}
			require_cache($class_file);
			$this->adapter_instance = new $class_name($adapter_config);
		}
		return $this->adapter_instance;
	}
	
	public function __call($method_name, $method_args) {
		if (method_exists($this, $method_name))
			return call_user_func_array(array(& $this, $method_name), $method_args);
		elseif (
			!empty($this->adapter_instance)
			&& ($this->adapter_instance instanceof login_abstract)
			&& method_exists($this->adapter_instance, $method_name)
		) 
		return call_user_func_array(array(& $this->adapter_instance, $method_name), $method_args);
	}	
}
?>