<?php
abstract class login_abstract
{
	protected $config = array();
	public function set_config($config) {
		foreach ($config as $key => $value) $this->config[$key] = $value;
		return $this;
	}
    public function get_code() {
        $prepare_data = $this->getpreparedata();
        return $this->config['gateway_url'].http_build_query($prepare_data);
	}
    // 同步接口
    abstract public function _login();
	abstract public function getpreparedata();
}