<?php 
/**
 *      [Haidao] (C)2013-2099 Dmibox Science and technology co., LTD.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.haidao.la
 *      tel:400-600-2042
 */
class plugin extends hd_base
{
	protected $plugin_dir;
	protected $plugin_path;
	protected $config;
	protected $pluginvar;
	protected $identifier;
	
	public function __construct($identifier = '') {
		$this->getInstance($identifier);
	}

	public function getInstance($identifier) {
		$pluginvars = cache('pluginvars','','common');
		$plugins = cache('plugins','','common');
		$this->identifier = $identifier;
		$this->plugin_dir = PLUGIN_PATH.$identifier;
		$this->plugin_path = str_replace(DOC_ROOT, __ROOT__, $this->plugin_dir);
		$this->pluginvar = $pluginvars[$identifier];
		$this->config = $plugins[$identifier];
	}
}