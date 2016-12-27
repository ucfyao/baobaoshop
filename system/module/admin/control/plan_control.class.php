<?php
hd_core::load_class('init', 'goods');
class plan_control extends init_control {
	public function __construct() {
		$this->app_service = $this->load->service('admin/app');
		$this->cloud_service = $this->load->service('admin/cloud');
	}
	/**
	 * [update description]
	 * @return [type] [description]
	 */
	public function app_update(){
		if(!cache('app_lock')){
			cache('app_lock',TIMESTAMP,'common',array('expire' => 7200));
			$this->app_service->build_cache();
			$this->load->service('admin/module')->build_cache();
		}
		return true;
	}
	/**
	 * [synchro_lists description]
	 * @return [type] [description]
	 */
	public function synchro_lists(){
		$this->app_service->synchro_lists();
		return true;
	}
	/**
	 * [update description]
	 * @return [type] [description]
	 */
	public function cloud_update(){
		if(!cache('cloud_lock')){
            cache('cloud_lock',TIMESTAMP,'common',array('expire' => 7200));
			$r = $this->cloud_service->update_site_userinfo();
        }
		return TRUE;
	}
}